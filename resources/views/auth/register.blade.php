<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SIGMA</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header -->
    @include('auth.component.headerauth')

    <!-- Register Form -->
    <main class="flex-1 flex items-center justify-center py-12">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Daftar Akun Baru</h2>

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

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label for="nim" class="block text-gray-700 font-medium mb-1">NIM</label>
                    <input id="nim" type="text" name="no_induk" value="{{ old('name') }}" required autofocus autocomplete="nim"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">Email</label>
                    <input id="email" type="email" name="email" value="" required autocomplete="username"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <!-- Prodi Dropdown -->
                <div class="max-w-md mx-auto mt-10">
                    <label for="prodi" class="block text-gray-700 font-medium mb-1">Program Studi</label>
                    <select id="prodi" name="prodi_id" class="w-full"></select>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-gray-700 font-medium mb-1">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Sudah punya akun?</a>
                    <button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                        Daftar
                    </button>
                </div>
            </form>
        </div>
    </main>

    <!-- Footer -->
   @include('auth.component.footerauth')

    <!-- Scripts -->
    @vite('resources/js/app.js')
    @stack('scripts')

    <!-- Tom Select for Prodi Dropdown -->
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    const select = document.getElementById('prodi');

    fetch('/get-prodi')
        .then(res => res.json())
        .then(({ options, optgroups }) => {
            new TomSelect(select, {
                options,
                optgroups,
                optgroupField: 'optgroup',
                valueField: 'value',
                labelField: 'text',
                searchField: 'text',
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                placeholder: 'Pilih Program Studi...',
                render: {
                    optgroup_header: function(data, escape) {
                        return `<div class="px-2 py-1 font-bold text-gray-600 bg-gray-100">${escape(data.label)}</div>`;
                    },
                    option: function(data, escape) {
                        return `<div class="px-3 py-2 hover:bg-blue-100">${escape(data.text)}</div>`;
                    },
                    item: function(data, escape) {
                        return `<div>${escape(data.text)}</div>`;
                    }
                }
            });
        });
    });
    </script>



</body>
</html>
