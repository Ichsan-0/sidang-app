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
    <nav class="flex items-center space-x-4">
        <a href="{{route('validator')}}" class="px-3 py-1 text-gray-600 rounded hover:bg-gray-400 hover:text-white">Validator</a>
        <a href="{{route('login')}}" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Login</a>
        <a href="{{route('register')}}" class="px-3 py-1 text-gray-600 rounded hover:bg-gray-400 hover:text-white">Register</a>
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
    <a href="#" class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Lihat Jadwal Seminar & Sidang</a>
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

<!-- Footer -->
<footer class="bg-gray-100 text-center py-4">
  <p class="text-sm text-gray-500">&copy; 2025 SIGMA. FTK UIN | All rights reserved.</p>
</footer>

</body>
</html>
