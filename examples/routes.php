<?php

use Illuminate\Support\Facades\Route;

// Authentication routes
Route::middleware('guest')->group(function () {
    // SIWE Login page
    Route::get('/login/ethereum', function () {
        return view('auth.siwe');
    })->name('login.ethereum');
});

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Example of displaying user's Ethereum address
    Route::get('/profile', function () {
        return view('profile', [
            'address' => auth()->user()->address
        ]);
    })->name('profile');
});
