<div class="col-12 col-md-6 mb-4 tugas-akhir-card">
  
  <div class="card shadow-sm border-0">
    <div class="card-header d-flex align-items-center justify-content-between py-3 mb-3">
      <h5 class="card-title text-black fw-bold mb-0">
        Tugas Akhir ({{ $ta->jenisPenelitian->nama ?? '-' }})
      </h5>
      <div class="d-flex gap-2">
        @if($ta->status->last()->status == 0)
        <button type="button" class="btn btn-sm btn-icon btn-warning editTugasAkhirBtn" data-id="{{ $ta->id }}" data-bs-toggle="tooltip" title="Edit">
            <i class="bx bx-edit"></i>
          </button>
          <button type="button" class="btn btn-sm btn-icon btn-danger deleteTugasAkhirBtn" data-id="{{ $ta->id }}" data-bs-toggle="tooltip" title="Hapus">
            <i class="bx bx-trash"></i>
          </button>
        @else
          <!-- Tidak tampilkan tombol edit/hapus -->

            @if($ta->has_revisi)
              <button type="button" class="btn btn-sm btn-outline-primary detailRevisiBtn" data-id="{{ $ta->id }}">
                <i class="bx bx-detail"></i> Detail Validasi/Revisi
              </button>
            @endif
          
        @endif
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <span class="fw-semibold ">Judul:</span>
        <span class="card-text">{{ $ta->judul ?? '-' }}</span>
      </div>
      @if($ta->bidangPeminatan)
        <div class="mb-3">
          <span class="fw-semibold ">Bidang Peminatan:</span>
          <span class="card-text">{{ $ta->bidangPeminatan->nama }}</span>
        </div>
      @endif
      <div class="mb-3">
        <span class="fw-semibold ">Pembimbing:</span>
        <span class="card-text">{{ $ta->pembimbing->name ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Latar Belakang:</span>
        <span class="card-text">{{ $ta->latar_belakang ?? '-' }}</span>
      </div>
      <div class="mb-3">
        <span class="fw-semibold ">Permasalahan:</span>
        <span class="card-text">{{ $ta->permasalahan ?? '-' }}</span>
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
          <span class="badge bg-secondary">Tidak ada</span>
        @endif
      </div>
      @php
        $status = $ta->status()->latest()->first();
      @endphp
      <div class="mb-3">
        @if($status && $status->status == 0)
          <span class="badge bg-secondary text-white">Usulan Disimpan, Belum diajukan</span>
        @elseif($status && $status->status == 1)
          <span class="badge bg-warning text-white">Telah diajukan, tanggal : {{ $status->created_at->format('d-m-Y') }}</span>
        @elseif($status && $status->status == 2)
          <span class="badge bg-primary">Pengusulan Disetujui tanggal : {{ $status->created_at->format('d-m-Y') }}</span>
        @elseif($status && $status->status == 3)
          <span class="badge bg-danger">Pengusulan Ditolak tanggal : {{ $status->created_at->format('d-m-Y') }}</span>
        @else
          <span class="badge bg-info">{{ $status->status ?? '-' }}</span>
        @endif
      </div>
      @if($status && $status->status == 2)
        @if($ta->sk_proposal)
            <span for=""><strong>SK Proposal</strong></span>
            <a href="{{ route('validasi-sk.cetak', ['id' => $ta->sk_proposal->id]) }}" class="badge bg-primary" target="_blank">
                <i class="bx bx-file"></i> Lihat SK Proposal
            </a>
        @else
            <span class="badge bg-warning text-white">Menunggu Tinjauan Prodi</span>
        @endif
      @endif
    </div>
  </div>
</div>
