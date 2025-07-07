@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data Prodi Fakultas</h5>
      <button class="btn btn-primary" id="addBtn">
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
<div class="modal fade" id="prodiModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="prodiForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Prodi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_prodi" class="form-label">Nama Prodi</label>
            <input type="text" class="form-control" name="nama_prodi" required>
          </div>
          <div class="mb-3">
            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
            <input type="text" class="form-control" name="tahun_ajaran" required>
          </div>
          <div class="mb-3">
            <label for="ka_prodi" class="form-label">Ka. Prodi</label>
            <input type="number" class="form-control" name="ka_prodi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="ket" required> </textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
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
       // show modal
    $('#addBtn').on('click', function () {
        $('#prodiForm')[0].reset();
        $('#prodiModal').modal('show');
    });

    // submit form
    $('#prodiForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: '{{ route("prodi.store") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#prodiModal').modal('hide');
                    table.ajax.reload();
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan!');
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endpush
