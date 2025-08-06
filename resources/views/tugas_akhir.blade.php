@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Informasi Tugas Akhir</h4>
    <button class="btn btn-primary" id="addBtn">
      <i class="bx bx-plus"></i> Usul Tugas Akhir
    </button>
  </div>
  <div class="row mb-4">
    <div class="col-md-12 col-xl-12">
      <div class="card bg-secondary text-white shadow-sm">
        <div class="card-body">
          <p class="card-text mb-0">Belum ada Tugas Akhir yang diusulkan</p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Modal Usul Tugas Akhir -->
<div class="modal fade" id="usulTAModal" tabindex="-1" aria-labelledby="usulTAModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="#" method="POST" class="modal-content" enctype="multipart/form-data">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title" id="usulTAModalLabel">Pengusulan Tugas Akhir</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="judul" class="form-label">Judul Tugas Akhir</label>
          <input type="text" class="form-control" id="judul" name="judul" required>
        </div>
        <div class="mb-3">
          <label for="selectTypeOpt" class="form-label">Jenis Tugas Akhir</label>
          <div class="d-flex gap-2 align-items-center">
            <select id="selectTypeOpt" class="form-select" name="type" style="flex: 1;">
              <option value="bg-primary" selected>Primary</option>
              <option value="bg-secondary">Secondary</option>
              <option value="bg-success">Success</option>
              <option value="bg-danger">Danger</option>
              <option value="bg-warning">Warning</option>
              <option value="bg-info">Info</option>
              <option value="bg-dark">Dark</option>
            </select>
            <button type="button" class="btn btn-icon btn-outline-primary" id="selectStatusOpt" title="Lihat Detail">
              <i class="bx bx-show"></i>
            </button>
          </div>
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label">Upload Draft</label>
          <input class="form-control" type="file" id="formFile" name="draft">
        </div>
        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="pembimbing" class="form-label">Pembimbing</label>
          <input type="text" class="form-control" id="pembimbing" name="pembimbing" placeholder="Nama Pembimbing" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim Usulan</button>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
  document.getElementById('addBtn').addEventListener('click', function() {
    var modal = new bootstrap.Modal(document.getElementById('usulTAModal'));
    modal.show();
  });
</script>
@endpush
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

@endpush
