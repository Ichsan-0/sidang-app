{{-- filepath: resources/views/layout/_card_tugas_akhir.blade.php --}}
<div class="col-md-12 col-xl-6 mb-4 tugas-akhir-card">
  <div class="card shadow-sm">
      <h5 class="card-header text-black">
        Tugas Akhir ( {{ $ta->jenisPenelitian->nama ?? '-' }} )
      </h5>
      <div class="card-body">
      <div class="mt-3 mb-3">
        <strong>Judul:</strong>
        <ul class="mb-1">
          @foreach($ta->judul as $judul)
            <li class="mb-2">{{ $judul->judul }}</li>
          @endforeach
        </ul>
      </div>
      <div class="mb-3">
        <strong>Bidang Peminatan :</strong> {{ $ta->bidangPeminatan->nama ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Pembimbing :</strong> {{ $ta->pembimbing->name ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Deskripsi :</strong> {{ $ta->deskripsi ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Draft :</strong>
        @if($ta->file)
          <a href="{{ asset('storage/'.$ta->file) }}" target="_blank" class="badge bg-primary">Lihat Draft</a>
        @else
          <span class="badge bg-secondary">Belum ada</span>
        @endif
      </div>
      <div class="mb-3">
        <strong>Status:</strong>
        @php
          $status = $ta->status()->latest()->first();
        @endphp
        @if($status)
          <span class="badge bg-info">{{ $status->status }}</span>
          <small class="text-muted">{{ $status->catatan }}</small>
        @else
          <span class="badge bg-secondary">Belum ada status</span>
        @endif
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-warning btn-sm editTugasAkhirBtn" data-id="{{ $ta->id }}">
          <i class="bx bx-edit"></i> 
        </button>
        <button class="btn btn-danger btn-sm deleteTugasAkhirBtn" data-id="{{ $ta->id }}">
          <i class="bx bx-trash"></i> 
        </button>
      </div>
    </div>
  </div>
</div>