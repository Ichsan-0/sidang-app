<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validator - SIGMA</title>
    @vite('resources/css/app.css')
    
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Header -->
    @include('auth.component.headerauth')

    <!-- Login Form -->
    <main class="flex-1 flex items-center justify-center py-12">
        <div class="w-full max-w-xl bg-white rounded-lg shadow-lg p-8">
            <!-- Judul -->
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">
                Validasi Surat Keterangan (SK)
            </h2>

            <p class="text-sm text-justify text-gray-600 mb-3">
                Masukkan detail atau nomor identifikasi unik dari Surat Keterangan (SK) yang diterbitkan melalui Sistem Informasi Tugas Akhir, seperti SK Pembimbing Proposal Skripsi dan lainnya.
                Sistem akan melakukan pemeriksaan keaslian dokumen berdasarkan data yang tersimpan di database.
            </p>

            <!-- Notifikasi Error -->
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

            <!-- Status -->
            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-100 border border-green-400 px-4 py-2 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form Validator -->


            <div class="space-y-4">
                <label for="nomor_sk" class="block text-gray-700 font-medium">
                    Masukkan Nomor SK
                </label>
                    <div class="flex items-center space-x-2">
                        <input id="nomor_sk" type="text"
                            class="flex-1 px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                            placeholder="Contoh: SK-PTI-2025-001" />
                        <button type="button" id="btnScan"
                            class="flex items-center px-3 py-2 bg-gray-200 rounded hover:bg-gray-300"
                            title="Pindai QR Code">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <rect x="3" y="3" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <rect x="15" y="3" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <rect x="3" y="15" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <rect x="15" y="15" width="6" height="6" rx="1.5" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M9 12h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M12 9v6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Scan
                        </button>
                    </div>
                    <p class="text-[10px] text-gray-400 mt-1">Anda dapat mengetik nomor SK secara manual atau menggunakan kamera untuk memindai QR Code pada SK agar nomor terisi otomatis.</p>
                <button id="btnValidate"
                    class="w-full px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 font-semibold">
                    Validasi SK
                </button>
            </div>
            <!-- Disclaimer -->
            <div class="mt-8 text-sm text-gray-500 text-justify">
                <p><span class="font-semibold">Disclaimer:</span>
                    Alat ini melakukan pemeriksaan keaslian Surat Keterangan (SK) berdasarkan data yang ada di database.
                </p>
            </div>
             <!-- Loading -->
            <div id="loading" class="hidden text-center mt-4 text-gray-600">
                <svg class="animate-spin h-6 w-6 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p>Sedang memvalidasi...</p>
            </div>

            <!-- Hasil -->
            <div id="result" class="hidden mt-6"></div>
            <div id="reader" class="hidden mt-4"></div>
        </div>
    </main>


    <!-- Footer -->
   @include('auth.component.footerauth')

    <!-- Scripts -->
    @vite('resources/js/app.js')
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
let html5QrCode;

document.getElementById('btnScan').addEventListener('click', function() {
    let reader = document.getElementById("reader");

    if (reader.classList.contains("hidden")) {
        reader.classList.remove("hidden");
        html5QrCode = new Html5Qrcode("reader");
        const config = { fps: 10, qrbox: 250 };

        html5QrCode.start(
            { facingMode: "environment" }, // kamera belakang hp
            config,
            qrCodeMessage => {
                document.getElementById("nomor_sk").value = qrCodeMessage;
                html5QrCode.stop();
                reader.classList.add("hidden");
            },
            errorMessage => {
                // bisa tambahkan log error jika perlu
            })
        .catch(err => {
            alert("Gagal membuka kamera: " + err);
        });
    } else {
        html5QrCode.stop();
        reader.classList.add("hidden");
    }
});

document.getElementById('btnValidate').addEventListener('click', function() {
    let nomor = document.getElementById('nomor_sk').value;
    let result = document.getElementById('result');
    let loading = document.getElementById('loading');

    result.classList.add('hidden');
    loading.classList.remove('hidden');
    result.innerHTML = '';

    fetch("{{ route('validator.check') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ nomor_sk: nomor })
    })
    .then(res => res.json())
    .then(data => {
        loading.classList.add('hidden');
        result.classList.remove('hidden');

        if (data.status === "ok") {
            let rows = "";
            for (const [key, value] of Object.entries(data.data)) {
                rows += `
                  <tr>
                    <td class="font-medium py-1 pr-4">${key}</td>
                    <td>${value}</td>
                  </tr>`;
            }

            result.innerHTML = `
              <div class="p-5 bg-green-50 border border-green-300 rounded-lg shadow-sm">
                <div class="flex items-start mb-4">
                    <svg class="h-6 w-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-800">
                      ${data.message}
                    </p>
                </div>
                <h3 class="font-bold mb-2">Detail SK</h3>
                <table class="w-full text-sm">
                  ${rows}
                </table>
              </div>`;
        } else {
            result.innerHTML = `
              <div class="p-5 bg-red-50 border border-red-300 rounded-lg shadow-sm text-red-700">
                ${data.message}
              </div>`;
        }
    })
    .catch(err => {
        loading.classList.add('hidden');
        result.classList.remove('hidden');
        result.innerHTML = `<div class="text-red-600">Terjadi kesalahan: ${err}</div>`;
    });
});
</script>
    
</body>
</html>
