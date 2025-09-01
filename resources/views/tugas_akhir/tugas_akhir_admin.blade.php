@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Informasi Usulan Judul</h4>
  </div>
  
  
  {{-- Tampilkan daftar tugas akhir untuk dosen atau admin --}}
  <div class="card mt-4">
    <div class="card-header">
      <h5 class="mb-0">Daftar Usulan Tugas Akhir</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table id="TugasAkhir" class="table table-hover">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Status</th>
              <th>Nama Mahasiswa</th>
              <th>Judul Diajukan</th>
              <th>Jenis Tugas Akhir</th>
              <th>Bidang Peminatan</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
  $('#TugasAkhir').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("tugas-akhir.ajax") }}',
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'status', name: 'status', orderable: true},
      { data: 'nama_mahasiswa', name: 'nama_mahasiswa', orderable: false, searchable: false },
      { data: 'jumlah_judul', name: 'jumlah_judul', orderable: false, searchable: false },
      { data: 'jenis_tugas_akhir', name: 'jenis_tugas_akhir', orderable: false, searchable: false },
      { data: 'bidang_peminatan', name: 'bidang_peminatan', orderable: false, searchable: false },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });
});
</script>

@endpush
@endsection

