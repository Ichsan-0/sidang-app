<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>SIGMA - Sistem Informasi Tugas Akhir Mahasiswa</title>
@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

<!-- Header -->
<header class="bg-white shadow-sm sticky top-0 w-full z-50">
  <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
    <div class="flex items-center space-x-2">
      <img src="{{ asset('storage/logo_uin.png') }}" alt="Logo" class="h-8">
      <span class="text-lg font-semibold text-gray-800">SIGMA</span>
    </div>
    <nav class="flex items-center space-x-1">
        <a href="{{route('validator')}}" class="px-2 py-1 text-gray-600 rounded hover:bg-gray-400 hover:text-white">Validator</a>
        <a href="{{route('login')}}" class="px-2 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
        <a href="{{route('register')}}" class="px-2 py-1 text-gray-600 rounded hover:bg-gray-400 hover:text-white">Register</a>
    </nav>
  </div>
</header>

<!-- Hero -->
<section class="relative text-center py-20 overflow-hidden">
  <!-- Gradient Background -->
  <div class="absolute inset-0 -z-10 flex justify-center items-center">
    <div class="w-[500px] h-[500px] bg-gradient-to-br from-blue-400 via-purple-400 to-pink-400 rounded-full blur-[120px] opacity-30"></div>
  </div>

  <h1 class="text-4xl md:text-5xl font-bold text-gray-800">
    Selamat Datang di <span class="text-blue-600">SIGMA</span>
  </h1>
  <p class="mt-4 text-gray-600 max-w-xl mx-auto">
    Sistem Informasi Tugas Akhir Mahasiswa yang hadir untuk menyederhanakan dan mengoptimalkan seluruh proses administrasi tugas akhir, mulai dari proposal hingga sidang kelulusan.
  </p>
  <div class="mt-6 flex justify-center space-x-4">
    <a href="#" id="btnPanduanSK" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Panduan pengajuan judul</a>
  </div>
</section>  

<!-- Fitur -->
<section class="py-16 bg-gray-50">
  <h2 class="text-center text-sm font-semibold text-blue-600">Fitur Unggulan</h2>
  <h3 class="text-center text-3xl font-bold text-gray-800 mt-2">Semua yang Anda Butuhkan di Satu Tempat</h3>
  <p class="text-center text-gray-600 max-w-xl mx-auto mt-4">
    SIGMA mengintegrasikan berbagai fitur untuk mendukung kelancaran studi akhir Anda.
  </p>

  <div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 px-4">
    <div class="bg-white rounded shadow p-6 text-center">
      <div class="text-blue-600 text-4xl mb-3">
        📄
      </div>
      <h4 class="font-semibold text-lg text-gray-800">Pembuatan SK Otomatis</h4>
      <p class="text-sm text-gray-600 mt-2">
        Proses pembuatan SK proposal dan skripsi menjadi lebih cepat, mudah, dan terstandarisasi.
      </p>
    </div>
    <div class="bg-white rounded shadow p-6 text-center">
      <div class="text-green-600 text-4xl mb-3">
        📖
      </div>
      <h4 class="font-semibold text-lg text-gray-800">Manajemen Skripsi</h4>
      <p class="text-sm text-gray-600 mt-2">
        Pantau progres, unggah dokumen, dan kelola bimbingan skripsi dalam satu platform terpusat.
      </p>
    </div>
    <div class="bg-white rounded shadow p-6 text-center">
      <div class="text-yellow-500 text-4xl mb-3">
        📅
      </div>
      <h4 class="font-semibold text-lg text-gray-800">Penjadwalan Sidang</h4>
      <p class="text-sm text-gray-600 mt-2">
        Sistem penjadwalan sidang yang fleksibel untuk mahasiswa, dosen, dan penguji.
      </p>
    </div>
    <div class="bg-white rounded shadow p-6 text-center">
      <div class="text-red-500 text-4xl mb-3">
        📝
      </div>
      <h4 class="font-semibold text-lg text-gray-800">Absensi Digital</h4>
      <p class="text-sm text-gray-600 mt-2">
        Catat kehadiran peserta dan penguji sidang secara digital, praktis dan tanpa kertas.
      </p>
    </div>
  </div>
</section>

<!-- Modal Panduan Pengajuan Judul -->
<div id="modalPanduanSK" 
     x-data="{ step: 1 }" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">

  <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full p-8 relative animate-fadeIn">
    <!-- Tombol Close -->
    <button id="closePanduanSK" 
            class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-2xl">&times;</button>

    <!-- Header -->
    <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Panduan Pembuatan SK Otomatis</h2>
    <p class="text-gray-500 text-center mb-6">Mulai dari Pengajuan Judul hingga SK Terbit</p>

    <!-- Step Indicator -->
    <div class="flex justify-between items-center mb-8">
      <template x-for="i in 4" :key="i">
        <div class="flex-1 flex items-center">
          <div class="flex flex-col items-center" :class="step >= i ? 'text-blue-600' : 'text-gray-400'">
            <div class="w-10 h-10 flex items-center justify-center rounded-full font-bold"
                 :class="step >= i ? 'bg-blue-600 text-white' : 'bg-gray-200'">
              <span x-text="i"></span>
            </div>
            <p class="mt-2 text-sm font-medium" x-text="[
              'Pengajuan Judul', 
              'Persetujuan & Revisi', 
              'Input Data SK', 
              'SK Terbit'
            ][i-1]"></p>
          </div>
          <div class="flex-1 border-t-2 mx-2" 
               :class="i < 4 ? (step > i ? 'border-blue-600' : 'border-gray-300') : ''"></div>
        </div>
      </template>
    </div>

    <!-- Konten Step -->
    <div class="text-gray-700 text-sm leading-relaxed space-y-4">
      <!-- Step 1 -->
      <div x-show="step === 1">
        <p class="font-bold">Langkah 1: Ajukan Judul Proposal</p>
        <ul class="list-disc pl-6 space-y-1">
          <li>Login ke akun SIGMA Anda sebagai mahasiswa.</li>
          <li>Pilih menu <b>Usul Tugas Akhir</b> dan isi formulir pengajuan judul dengan lengkap.</li>
          <li>Disarankan untuk berkonsultasi dengan dosen pembimbing sebelum mengajukan judul.</li>
          <li>Pastikan judul dan dokumen pendukung sudah benar sebelum dikirim.</li>
        </ul>
      </div>

      <!-- Step 2 -->
      <div x-show="step === 2">
        <p class="font-bold">Langkah 2: Persetujuan & Revisi</p>
        <ul class="list-disc pl-6 space-y-1">
            <li>Dosen pembimbing akan memeriksa usulan Anda.</li>
            <li>Dosen dapat menolak atau menyetujui usulan Anda.</li>
            <li>Jika disetujui namun perlu perubahan, dosen akan merevisi judul Anda.</li>
            <li>Perhatikan catatan revisi yang diberikan; mahasiswa wajib memperbaiki isi pembahasan pada tahap pembuatan proposal.</li>
        </ul>
      </div>

      <!-- Step 3 -->
      <div x-show="step === 3">
        <p class="font-bold">Langkah 3: Input Data SK</p>
        <ul class="list-disc pl-6 space-y-1">
            <li>Admin/Prodi dapat menolak atau menyetujui usulan Anda.</li>
            <li>Jika disetujui, Admin akan memproses SK Anda.</li>
          <li>Mahasiswa dapat memantau status SK pada dashboard.</li>
        </ul>
      </div>

      <!-- Step 4 -->
      <div x-show="step === 4">
        <p class="font-bold">Langkah 4: SK Terbit</p>
        <ul class="list-disc pl-6 space-y-1">
            <li>SK Proposal akan diterbitkan secara resmi oleh sistem.</li>
            <li>Mahasiswa dapat mengunduh SK langsung melalui dashboard.</li>
            <li>Selama SK masih aktif, pengajuan judul baru tidak dapat dilakukan.</li>
            <li>Menu <b>Validator</b> di bagian atas dapat digunakan untuk melihat data SK yang telah diajukan.</li>
        </ul>
      </div>
    </div>

    <!-- Footer Navigation -->
    <div class="mt-8 flex justify-between items-center">
      <button @click="step > 1 ? step-- : null"
              class="px-4 py-2 text-gray-600 hover:text-gray-800"
              :class="step === 1 ? 'opacity-40 cursor-not-allowed' : ''">
        Kembali
      </button>

      <button @click="step < 4 ? step++ : null"
              class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700"
              x-text="step < 4 ? 'Selanjutnya' : 'Selesai'">
      </button>
    </div>
  </div>
</div>

<!-- Alpine.js -->
<script src="//unpkg.com/alpinejs" defer></script>

<!-- Animasi Fade -->
<style>
@keyframes fadeIn {
  from {opacity: 0; transform: translateY(20px);}
  to {opacity: 1; transform: translateY(0);}
}
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}
</style>



<!-- Footer -->
<footer class="bg-gray-100 text-center py-4">
  <p class="text-sm text-gray-500">&copy; 2025 SIGMA. FTK UIN | All rights reserved.</p>
</footer>

<script>
document.getElementById('btnPanduanSK').addEventListener('click', function(e) {
  e.preventDefault();
  document.getElementById('modalPanduanSK').classList.remove('hidden');
});
document.getElementById('closePanduanSK').onclick = function() {
  document.getElementById('modalPanduanSK').classList.add('hidden');
};
</script>

</body>
</html>
