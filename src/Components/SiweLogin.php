<?php

namespace Scriptoshi\LivewireSiwe\Components;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use kornrunner\Keccak;
use Elliptic\EC;

class SiweLogin extends Component
{
    public ?string $error = null;
    public bool $isLoaded = false;
    public string $nonce;

    /**
     * Initialize component state
     */
    public function mount()
    {
        $this->generateNonce();
    }

    /**
     * Generate a strong random nonce
     */
    public function generateNonce(): string
    {
        $this->nonce = bin2hex(random_bytes(16));
        return $this->nonce; // 32 character string
    }

    /**
     * Verify and authenticate a SIWE message and signature
     */
    public function siweAuth($data): void
    {
        $this->error = null;
        try {
            // Extract data from the signed message
            $message = $data['message'] ?? null;
            $signature = $data['signature'] ?? null;

            if (!$message || !$signature) {
                throw new \Exception('Invalid authentication data');
            }

            // Parse SIWE message
            $parsedMessage = $this->parseSiweMessage($message);

            // Verify the nonce matches to prevent replay attacks
            if ($parsedMessage['nonce'] !== $this->nonce) {
                throw new \Exception('Invalid nonce');
            }

            // Verify signature matches the message and was signed by claimed address
            $address = $parsedMessage['address'];
            if (!$this->verifySignature($message, $signature, $address)) {
                throw new \Exception('Invalid signature');
            }

            // Authenticate or create user based on Ethereum address
            $user = $this->authenticate($address);

            // Regenerate nonce after successful auth
            $this->nonce = $this->generateNonce();

            // Redirect to configured URL after successful login
            $redirectUrl = config('livewire-siwe.redirect_url', '/dashboard');

            // If the redirect URL is a route name, resolve it
            if (config('livewire-siwe.redirect_is_route', true)) {
                $this->redirect(route($redirectUrl, absolute: false));
            } else {
                $this->redirect($redirectUrl);
            }
        } catch (\Exception $e) {
            $this->error = 'Authentication error: ' . $e->getMessage();
        }
    }

    /**
     * Parse the SIWE message format to extract parameters
     */
    protected function parseSiweMessage(string $message): array
    {
        $lines = explode("\n", $message);
        $result = [];

        // Extract Ethereum address (line 2)
        $result['address'] = trim($lines[1] ?? '');

        // Process other parameters from the message
        foreach ($lines as $line) {
            if (strpos($line, 'URI: ') === 0) {
                $result['uri'] = substr($line, 5);
            } elseif (strpos($line, 'Version: ') === 0) {
                $result['version'] = substr($line, 9);
            } elseif (strpos($line, 'Chain ID: ') === 0) {
                $result['chainId'] = (int) substr($line, 10);
            } elseif (strpos($line, 'Nonce: ') === 0) {
                $result['nonce'] = substr($line, 7);
            } elseif (strpos($line, 'Issued At: ') === 0) {
                $result['issuedAt'] = substr($line, 11);
            } elseif (strpos($line, 'Expiration Time: ') === 0) {
                $result['expirationTime'] = substr($line, 17);
            }
        }

        // Extract domain from first line
        if (isset($lines[0]) && preg_match('/^(.+) wants you to sign in with your Ethereum account:$/', $lines[0], $matches)) {
            $result['domain'] = $matches[1];
        }

        return $result;
    }

    /**
     * Verify the SIWE signature
     */
    protected function verifySignature(string $message, string $signature, string $address): bool
    {
        $messageLength = strlen($message);
        $hash = Keccak::hash("\x19Ethereum Signed Message:\n{$messageLength}{$message}", 256);

        if (!preg_match('/^0x[0-9a-fA-F]{130}$/', $signature)) {
            throw new \TypeError('Invalid signature format');
        }

        $v = hexdec(substr($signature, 130, 2));
        $recid = $v - 27;

        if ($recid < 0 || $recid > 1) {
            return false;
        }

        $r = substr($signature, 2, 64);
        $s = substr($signature, 66, 64);

        $ec = new EC('secp256k1');
        $pubKey = $ec->recoverPubKey($hash, ['r' => $r, 's' => $s], $recid);
        $recoveredAddress = $this->pubKeyToAddress($pubKey);

        return strtolower($address) === strtolower($recoveredAddress);
    }

    /**
     * Convert public key to Ethereum address
     */
    protected function pubKeyToAddress($pubKey): string
    {
        $pubKeyHex = $pubKey->encode('hex');
        return '0x' . substr(Keccak::hash(substr(hex2bin($pubKeyHex), 1), 256), 24);
    }

    /**
     * Login or Register user
     */
    protected function authenticate($address)
    {
        // Get the user model class from config
        $userModelClass = config('livewire-siwe.user_model');

        // If no model is configured, throw an exception
        if (!$userModelClass || !class_exists($userModelClass)) {
            throw new \Exception('User model not configured properly. Please set livewire-siwe.user_model in your config.');
        }

        if ($userModelClass::where('address', $address)->exists()) {
            return $this->login($address, $userModelClass);
        }

        return $this->register($address, $userModelClass);
    }

    /**
     * Log in existing user
     */
    protected function login($address, $userModelClass)
    {
        $user = $userModelClass::where('address', $address)->first();
        Auth::login($user);

        // Allow for custom post-login hooks
        if (is_callable(config('livewire-siwe.post_login_callback'))) {
            call_user_func(config('livewire-siwe.post_login_callback'), $user);
        }

        return $user;
    }

    /**
     * Register new user with Ethereum address
     */
    protected function register($address, $userModelClass)
    {
        // Get required fields from config or use default
        $requiredFields = config('livewire-siwe.required_user_fields', ['name', 'address']);

        // Base user data with just the address
        $userData = [
            'address' => $address,
        ];

        // Add default name if required
        if (in_array('name', $requiredFields)) {
            $userData['name'] = substr($address, 0, 6) . '...' . substr($address, -4);
        }

        // Apply custom user data if a callback is defined
        if (is_callable(config('livewire-siwe.user_data_callback'))) {
            $userData = call_user_func(config('livewire-siwe.user_data_callback'), $address, $userData);
        }

        // Create the user
        $user = $userModelClass::create($userData);

        // Fire registered event if configured to do so
        if (config('livewire-siwe.fire_registered_event', true)) {
            event(new Registered($user));
        }

        // Log the user in
        Auth::login($user);

        // Allow for custom post-registration hooks
        if (is_callable(config('livewire-siwe.post_register_callback'))) {
            call_user_func(config('livewire-siwe.post_register_callback'), $user);
        }

        return $user;
    }

    /**
     * Generate SIWE parameters for AppKit
     */
    public function getSiweConfigJson(): string
    {
        $config = [
            'domain' => request()->getHost(),
            'uri' => url('/'),
            'statement' => config('livewire-siwe.statement', 'Sign in with your Ethereum account to access this application'),
            'chainId' => config('livewire-siwe.chain_id', 1), // Default to Ethereum mainnet
            'nonce' => $this->nonce,
            'version' => '1',
        ];

        return json_encode($config);
    }

    /**
     * Get AppKit project ID from config
     */
    public function getProjectIdProperty(): string
    {
        return config('livewire-siwe.project_id', '');
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire-siwe::siwe-login');
    }
}
