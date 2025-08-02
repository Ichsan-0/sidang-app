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
        <table id="tahunTable" class="table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>Keterangan</th>
              <th>Periode Awal</th>
              <th>Periode Akhir</th>
              <th>Status</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="tahunModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="tahunForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="tahun_id">
          <div class="mb-3">
            <label class="form-label">Nama Tahun Ajaran</label>
            <input type="text" class="form-control" name="nama" id="nama" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Periode Awal</label>
            <input type="date" class="form-control" name="periode_awal" id="periode_awal" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Periode Akhir</label>
            <input type="date" class="form-control" name="periode_akhir" id="periode_akhir" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="is_aktif" id="is_aktif">
              <option value="y">Aktif</option>
              <option value="n">Tidak Aktif</option>
            </select>
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
    function formatDate(data) {
        if (!data) return '';
        const date = new Date(data);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = String(date.getFullYear());
        return `${day} / ${month} / ${year}`;
    }

    var table = $('#tahunTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("tahun.ajax") }}',
        autoWidth: false, 
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'periode_awal', name: 'periode_awal', render: formatDate },
            { data: 'periode_akhir', name: 'periode_akhir', render: formatDate },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // show modal for add
    $('#addBtn').on('click', function () {
        $('#tahunForm')[0].reset();
        $('#tahun_id').val('');
        $('.modal-title').text('Tambah Tahun Ajaran');
        $('#tahunModal').modal('show');
    });


    // show modal for edit
   $(document).on('click', '.editBtn', function () {
      var id = $(this).data('id');
      $.get('/tahun/edit/' + id, function (data) {
          $('#tahun_id').val(data.id); // <-- perbaiki di sini
          $('#nama').val(data.nama);
          $('#periode_awal').val(data.periode_awal);
          $('#periode_akhir').val(data.periode_akhir);
          $('#is_aktif').val(data.is_aktif);
          $('.modal-title').text('Edit Tahun Ajaran');
          $('#tahunModal').modal('show');
      });
  });

    // submit form (add/update)
    $('#tahunForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#tahun_id').val();
        var url = id ? '/tahun/update/' + id : '/tahun/store';
        var method = id ? 'POST' : 'POST';
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#tahunModal').modal('hide');
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
            url: '/tahun/delete/' + id,
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
