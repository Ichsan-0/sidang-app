{{-- filepath: resources/views/layout/_card_tugas_akhir.blade.php --}}

<div class="col-md-12 col-xl-6 mb-4 tugas-akhir-card">
  <div class="card shadow-sm">
      <h5 class="fw-bold mb-0 p-3">
        Tugas Akhir ( {{ $ta->jenisPenelitian->nama ?? '-' }} )
      </h5>
      <div class="card-body">
      <div class="mb-3">
        <strong>Judul :</strong> {{ $ta->judul ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Bidang Peminatan :</strong> {{ $ta->bidangPeminatan->nama ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Pembimbing :</strong> {{ $ta->pembimbing->name ?? '-' }}
      </div>
      <div class="mb-3">
        <strong>Catatan Usulan :<d/strong> {{ $ta->deskripsi ?? '-' }}
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
          @if($status->status == 1)
            <span class="badge bg-warning">Menunggu diperiksa</span>
          @elseif($status->status == 2)
            <span class="badge bg-success">Disetujui</span>
          @elseif($status->status == 3)
            <span class="badge bg-danger">Ditolak</span>
          @else
            <span class="badge bg-info">{{ $status->status }}</span>
          @endif
      </div>
      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-icon btn-warning editTugasAkhirBtn" data-id="{{ $ta->id }}">
          <i class="bx bx-edit"></i> 
        </button>
        <button class="btn btn-icon btn-danger deleteTugasAkhirBtn" data-id="{{ $ta->id }}">
          <i class="bx bx-trash"></i> 
        </button>
      </div>
    </div>
  </div>
</div>
