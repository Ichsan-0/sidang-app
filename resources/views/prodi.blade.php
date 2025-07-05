@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data Prodi Fakultas</h5>
      <button class="btn btn-primary">
        <i class="bx bx-plus"></i> Tambah Prodi
      </button>
    </div>

    <div class="card-body">
      

      <div class="table-responsive text-nowrap">
        <table id="prodiTable" class="table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Nama Prodi</th>
              <th>Tahun Ajaran</th>
              <th>Ka. Prodi</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>


@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    $('#prodiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("prodi.ajax") }}',
        autoWidth: false, 
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_prodi', name: 'nama_prodi' },
            { data: 'tahun_ajaran', name: 'tahun_ajaran' },
            { data: 'ka_prodi', name: 'ka_prodi' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });
});
</script>
@endpush
