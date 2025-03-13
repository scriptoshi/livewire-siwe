<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIWE Login Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @livewireStyles
    @vite(['resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-center mb-6">Sign In With Ethereum</h1>
        
        <div class="mb-4">
            <livewire:siwe-login />
        </div>
        
        <div class="mt-4 text-center text-sm text-gray-500">
            <p>Connect your Ethereum wallet to sign in</p>
        </div>
    </div>
    
    <!-- Scripts -->
    @livewireScripts
</body>
</html>
