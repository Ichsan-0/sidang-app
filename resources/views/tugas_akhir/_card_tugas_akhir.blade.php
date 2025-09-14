<div class="col-12 col-md-6 mb-4 tugas-akhir-card">
  
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary d-flex align-items-center justify-content-between py-3 mb-3">
      <h5 class="card-title text-white fw-bold mb-0">
        Tugas Akhir ({{ $ta->jenisPenelitian->nama ?? '-' }})
      </h5>
      <div class="d-flex gap-2">
        @if($ta->status->last()->status == 1)
          <!-- Tidak tampilkan tombol edit/hapus -->
        @else
          <button type="button" class="btn btn-sm btn-icon btn-warning editTugasAkhirBtn" data-id="{{ $ta->id }}" data-bs-toggle="tooltip" title="Edit">
            <i class="bx bx-edit"></i>
          </button>
          <button type="button" class="btn btn-sm btn-icon btn-danger deleteTugasAkhirBtn" data-id="{{ $ta->id }}" data-bs-toggle="tooltip" title="Hapus">
            <i class="bx bx-trash"></i>
          </button>
        @endif
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <span class="fw-semibold ">Judul:</span>
        <span class="card-text">{{ $ta->judul ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Bidang Peminatan:</span>
        <span class="card-text">{{ $ta->bidangPeminatan->nama ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Pembimbing:</span>
        <span class="card-text">{{ $ta->pembimbing->name ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Latar Belakang:</span>
        <span class="card-text">{{ $ta->latar_belakang ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Catatan Usulan:</span>
        <span class="card-text">{{ $ta->deskripsi ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Draft:</span>
        @if($ta->file)
          <a href="{{ asset('storage/'.$ta->file) }}" download class="badge bg-primary">
            <i class="bx bx-file"></i> Download Draft
          </a>
        @else
          <span class="badge bg-secondary">Belum ada</span>
        @endif
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Status:</span>
        @php
          $status = $ta->status()->latest()->first();
        @endphp
        @if($status && $status->status == 0)
          <span class="badge bg-primary text-white">Usulan Disimpan, Belum diajukan</span>
        @elseif($status && $status->status == 1)
          <span class="badge bg-warning text-white">Telah diajukan, Belum Diperiksa</span>
        @elseif($status && $status->status == 2)
          <span class="badge bg-success">Disetujui</span>
        @elseif($status && $status->status == 3)
          <span class="badge bg-danger">Ditolak</span>
        @else
          <span class="badge bg-info">{{ $status->status ?? '-' }}</span>
        @endif
      </div>
    </div>
  </div>
</div>
