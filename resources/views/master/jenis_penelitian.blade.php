@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data Jenis Penelitian</h5>
      <button class="btn btn-primary" id="addBtn">
        <i class="bx bx-plus"></i> Jenis Penelitian
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="jenisPenelitianTable" class="table table-bordered table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>No.</th>
              <th>Nama Jenis</th>
              <th>Kode</th>
              <th>Keterangan</th>
              <th></th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
    
  </div>
  
  
</div>

<div class="modal fade" id="jenisPenelitianModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="jenisPenelitianForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="jenis_penelitian_id">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Jenis Penelitian</label>
            <input type="text" class="form-control" name="nama" id="nama" required>
          </div>
          <div class="mb-3">
            <label for="kode" class="form-label">Kode</label>
            <input type="text" class="form-control" name="kode" id="kode" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="ket" id="ket"></textarea>
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
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
/* Agar dropdown Tom Select selalu di atas modal Bootstrap */
.ts-control {
    min-height: 38px; /* sesuai Bootstrap input */
    height: auto;
    width: 100%;
}
.ts-dropdown, .ts-dropdown.form-select {
    z-index: 1060 !important;
}
.ts-dropdown .optgroup-header {
      background-color: #e8f4ff;
      color: #007bff;
      font-weight: 600;
      padding: 8px 12px;
      font-size: 14px;
      border-bottom: 1px solid #cce5ff;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    var table = $('#jenisPenelitianTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("jenis-penelitian.ajax") }}',
        autoWidth: false,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'kode', name: 'kode' },
            { data: 'ket', name: 'ket' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // show modal for add
    $('#addBtn').on('click', function () {
        $('#jenisPenelitianForm')[0].reset();
        $('#jenis_penelitian_id').val('');
        $('.modal-title').text('Tambah Jenis Penelitian');
        $('#jenisPenelitianModal').modal('show');
    });

    // show modal for edit
    $(document).on('click', '.editBtn', function () {
        var id = $(this).data('id');
        $.get('/jenis-penelitian/edit/' + id, function (data) {
            $('#jenis_penelitian_id').val(data.id);
            $('#nama').val(data.nama);
            $('#kode').val(data.kode);
            $('#ket').val(data.ket);
            $('.modal-title').text('Edit Jenis Penelitian');
            $('#jenisPenelitianModal').modal('show');
        });
    });

    // submit form (add/update)
    $('#jenisPenelitianForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#jenis_penelitian_id').val();
        var url = id ? '/jenis-penelitian/update/' + id : '/jenis-penelitian/store';
        var method = 'POST';
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#jenisPenelitianModal').modal('hide');
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

    // delete
    $(document).on('click', '.deleteBtn', function () {
        if (!confirm('Yakin ingin menghapus data ini?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/jenis-penelitian/delete/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.success) {
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
