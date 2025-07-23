{{-- filepath: resources/views/layouts/guest-header.blade.php --}}
<header class="bg-white shadow-sm sticky top-0 w-full z-50">
    <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="https://img.icons8.com/fluency/48/shield.png" alt="Logo" class="h-8">
            <span class="text-lg font-semibold text-gray-800">SIGMA</span>
        </a>
        <nav class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="px-3 py-1 {{ request()->routeIs('login') ? 'bg-blue-600 text-white' : 'text-gray-600' }} rounded hover:bg-blue-700 hover:text-white">Login</a>
            <a href="{{ route('register') }}" class="px-3 py-1 {{ request()->routeIs('register') ? 'bg-blue-600 text-white' : 'text-gray-600' }} rounded hover:bg-blue-700 hover:text-white">Register</a>
        </nav>
    </div>
</header>