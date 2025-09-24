@extends('layout.app')

@section('content')
<div class="container d-flex flex-column justify-content-center align-items-center" style="min-height: 80vh;">
  <div class="card shadow-lg border-0 text-center p-5" style="max-width: 600px;">
    <h2 class="mb-3 text-primary fw-bold">Coming Soon!</h2>
    <p class="mb-4 text-muted">
      Fitur ini sedang dalam tahap pengembangan.<br>
      Kami sedang menyiapkan sesuatu yang menarik untuk Anda.<br>
      Silakan kembali lagi nanti.
    </p>
    <a href="{{ url()->previous() }}" class="btn btn-outline-primary mt-2">
      <i class="bx bx-arrow-back"></i> Kembali
    </a>
  </div>
</div>
@endsection
