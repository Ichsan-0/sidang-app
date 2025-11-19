@extends('layout.app')

@section('content')
<!-- Content -->
<div class="container-xxl flex-grow-1 container-p-y">
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">PIN atau No. Ijazah dan Transkrip</h5>
      <div class="d-flex gap-1">
        <button class="btn btn-sm btn-primary" id="addBtn">
          <i class="bx bx-plus"></i> Data
        </button>
        <button class="btn btn-sm btn-success" id="exportBtn">
          <i class="bx bx-download"></i> Export Data
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="table-responsive text-nowrap">
        <table id="userTable" class="table table-hover">
          <thead>
            <tr>
              <th>No.</th>
              <th>NIM</th>
              <th>Nama</th>
              <th>No. Transkrip</th>
              <th>No. Ijazah</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
<!-- Modal Data Akhir -->
<div class="modal fade" id="dataAkhirModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formDataAkhir">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalTitle">Tambah Data PIN Ijazah/Transkrip</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="id" id="data_id">
          <div class="mb-3">
            <label for="nim" class="form-label">NIM</label>
            <input type="number" name="nim" id="nim" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="nama" class="form-label">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="nomor_transkrip" class="form-label">No. Transkrip</label>
            <input type="text" name="nomor_transkrip" id="nomor_transkrip" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="pin_ijazah" class="form-label">No. Ijazah</label>
            <input type="text" name="pin_ijazah" id="pin_ijazah" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
<!-- / Content -->
@endsection
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});
$(function () {
    var table = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("pin-akhir.ajax") }}',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nim', name: 'nim' },
            { data: 'nama', name: 'nama' },
            { data: 'nomor_transkrip', name: 'nomor_transkrip' },
            { data: 'pin_ijazah', name: 'pin_ijazah' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    $('#addBtn').click(function() {
        $('#modalTitle').text('Tambah Data PIN Ijazah/Transkrip');
        $('#formDataAkhir')[0].reset();
        $('#data_id').val('');
        $('#dataAkhirModal').modal('show');
    });

    $('#formDataAkhir').on('submit', function(e) {
        e.preventDefault();
        var id = $('#data_id').val();
        var url = '/pin-akhir' + (id ? '/' + id : '');
        var method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                if(res.success) {
                    $('#dataAkhirModal').modal('hide');
                    $('#userTable').DataTable().ajax.reload();
                    alert(id ? 'Data berhasil diupdate!' : 'Data berhasil ditambahkan!');
                }
            },
            error: function(xhr) {
                alert('Gagal menyimpan data');
            }
        });
    });

    $(document).on('click', '.editBtn', function() {
        var id = $(this).data('id');
        $.get('/pin-akhir/' + id, function(res) {
            $('#modalTitle').text('Edit Data PIN Ijazah/Transkrip');
            $('#data_id').val(res.id);
            $('#nim').val(res.nim);
            $('#nama').val(res.nama);
            $('#nomor_transkrip').val(res.nomor_transkrip);
            $('#pin_ijazah').val(res.pin_ijazah);
            $('#dataAkhirModal').modal('show');
        });
    });

    $(document).on('click', '.deleteBtn', function() {
        if(!confirm('Hapus data ini?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/pin-akhir/' + id,
            method: 'DELETE',
            success: function(res) {
                if(res.success) {
                    $('#userTable').DataTable().ajax.reload();
                    alert('Data berhasil dihapus!');
                }
            },
            error: function() {
                alert('Gagal menghapus data');
            }
        });
    });
});
</script>

@endpush
