<div class="w-full">
    <!-- Hidden div for AppKit -->
    <div wire:ignore></div>

    <button id="siwe-login-button" type="button" x-data="{
        isLoaded: @entangle('isLoaded'),
        error: @entangle('error'),
        nonce: @entangle('nonce'),
        appkit: null,
        init() {
            this.isLoaded = true;
        },
        async handleAuth() {
           try {
                // Parse SIWE config from the component
                const siweConfig = JSON.parse('{{ $this->getSiweConfigJson() }}');
                // Add custom verification function that calls back to Livewire
                siweConfig.verifyMessage = async ({ message, signature }) => {
                    try {
                        // Send data to the Livewire component for verification
                        await $wire.siweAuth({ message, signature });
                        return true;
                    } catch (err) {
                        this.error = 'Authentication failed';
                        return false;
                    } finally {
                        this.isLoaded = true;
                    }
                };
                siweConfig.getNonce = async () => {
                    try {
                        const nonce = await $wire.generateNonce();
                        return nonce;
                    } catch (err) {
                        return siweConfig.nonce;
                    }
                }; 
                siweConfig.theme = '{{ session('theme','light') }}';
                // Initialize AppKit with SIWE config
                this.appKit = window.initSiwe(siweConfig, '{{ $this->projectId }}');
                await this.appKit.open();
            } catch (err) {
                this.error = 'Error: ' + err.message;
                this.isLoaded = true;
            }
        }
    }" x-on:click="handleAuth"
        class="relative group w-full bg-blue-50 text-blue-600 border border-blue-600 hover:bg-blue-600 hover:text-white transition-colors py-2 px-4 rounded-md flex items-center justify-center">
        <template x-if="!isLoaded">
            <svg class="animate-spin h-5 w-5 absolute left-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </template>
        <template x-if="isLoaded">
            <svg class="h-5 w-5 absolute left-5 group-hover:text-white" xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 320 512" fill="currentColor">
                <path
                    d="M311.9 260.8L160 353.6 8 260.8 160 0l151.9 260.8zM160 383.4L8 290.6 160 512l152-221.4-152 92.8z" />
            </svg>
        </template>

        <template x-if="error">
            <span class="text-red-500" x-text="error"></span>
        </template>
        <template x-if="!error">
            <span>Sign In With Ethereum</span>
        </template>
    </button>
</div>
