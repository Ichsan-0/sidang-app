<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIGMA</title>
    @vite('resources/css/app.css')
    
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header -->
    @include('auth.component.headerauth')

    <!-- Login Form -->
    <main class="flex-1 flex items-center justify-center py-12">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Masuk ke SIGMA</h2>

            @if ($errors->any())
                <div class="mb-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">
                        <ul class="text-sm list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email atau No. Induk -->
                <div>
                    <label for="login" class="block text-gray-700 font-medium mb-1">Email atau No. Induk</label>
                    <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus autocomplete="username"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-600 hover:underline" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="mt-6">
                    <button type="submit" class="w-full px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
   @include('auth.component.footerauth')

    <!-- Scripts -->
    @vite('resources/js/app.js')
    
</body>
</html>
