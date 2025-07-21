@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data Fakultas</h5>
      <button class="btn btn-primary" id="addBtn">
        <i class="bx bx-plus"></i> Tambah Fakultas
      </button>
    </div>

    <div class="card-body">
      

      <div class="table-responsive text-nowrap">
        <table id="fakultasTable" class="table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Fakultas</th>
              <th>Kode</th>
              <th>Keterangan</th>
              <th>Actions</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="fakultasModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="fakultasForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="fakultas_id">
          <div class="mb-3">
            <label for="nama" class="form-label">Nama Fakultas</label>
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
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(function () {
    var table = $('#fakultasTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("fakultas.ajax") }}',
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
        $('#fakultasForm')[0].reset();
        $('#fakultas_id').val('');
        $('.modal-title').text('Tambah Fakultas');
        $('#fakultasModal').modal('show');
    });

    // show modal for edit
    $(document).on('click', '.editBtn', function () {
        var id = $(this).data('id');
        $.get('/fakultas/edit/' + id, function (data) {
            $('#fakultas_id').val(data.id);
            $('#nama').val(data.nama);
            $('#kode').val(data.kode);
            $('#ket').val(data.ket);
            $('.modal-title').text('Edit Fakultas');
            $('#fakultasModal').modal('show');
        });
    });

    // submit form (add/update)
    $('#fakultasForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#fakultas_id').val();
        var url = id ? '/fakultas/update/' + id : '/fakultas/store';
        var method = id ? 'POST' : 'POST';
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#fakultasModal').modal('hide');
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
            url: '/fakultas/delete/' + id,
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
