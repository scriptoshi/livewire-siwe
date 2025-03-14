<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/');
    }

    public function with(): array
    {
        return [
            'user' => Auth::user(),
        ];
    }
}; ?>

<div class="min-h-screen bg-gray-100 flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-6">
        @auth
        <div class="text-center">
            <h2 class="text-2xl font-bold mb-4">Welcome!</h2>
            <p class="text-gray-600 mb-2">You are signed in with Ethereum</p>
            <div class="p-3 bg-blue-50 rounded-lg mb-4">
                <p class="font-mono text-sm break-all">{{ $user->address }}</p>
            </div>
            <button
                wire:click="logout"
                class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-md">
                Sign Out
            </button>
        </div>
        @else
        <h2 class="text-2xl font-bold text-center mb-6">Ethereum Login</h2>
        <div class="mb-4">
            <livewire:siwe-auth />
        </div>
        <div class="mt-4 text-center text-sm text-gray-500">
            <p>Connect your Ethereum wallet to sign in</p>
        </div>
        @endauth
    </div>
</div>