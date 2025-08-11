@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
      <h5 class="mb-0">Data Bidang Peminatan</h5>
      <button class="btn btn-primary" id="addBidangBtn">
        <i class="bx bx-plus"></i> Tambah Bidang
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="bidangPeminatanTable" class="table table-bordered table-striped align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>No.</th>
              <th>Nama Bidang</th>
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


<div class="modal fade" id="bidangModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="bidangForm">
      @csrf
      <input type="hidden" name="id" id="bidang_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_bidang" class="form-label">Nama Bidang</label>
            <input type="text" class="form-control" name="nama" id="nama_bidang" required>
          </div>
          <div class="mb-3">
            <label for="kode_bidang" class="form-label">Kode</label>
            <input type="text" class="form-control" name="kode" id="kode_bidang" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Pilih Prodi</label>
            <select class="form-select" name="prodi" id="prodi_bidang" required></select>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="ket" id="ket_bidang"></textarea>
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

    // Bidang Peminatan
    var bidangTable = $('#bidangPeminatanTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("bidang-peminatan.ajax") }}',
        autoWidth: false,
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama', name: 'nama' },
            { data: 'kode', name: 'kode' },
            { data: 'ket', name: 'ket' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // show/hide bidang button
    $('#jenisPenelitianTable').on('click', '.editBtn', function () {
        var id = $(this).data('id');
        $('#bidang_jenis_penelitian_id').val(id);
        $('#addBidangBtn').show();
        $('#bidangTitle').text('(' + $(this).closest('tr').find('td:eq(1)').text() + ')');
        bidangTable.ajax.url('{{ route("bidang-peminatan.ajax") }}').load();
    });

    // show modal for add bidang
    $('#addBidangBtn').on('click', function () {
        $('#bidangForm')[0].reset();
        $('#bidang_id').val('');
        loadProdiBidang();
        $('.modal-title').text('Tambah Bidang Peminatan');
        $('#bidangModal').modal('show');
    });

      $(document).on('click', '.editBidangBtn', function () {
        var id = $(this).data('id');
        $.get('/bidang-peminatan/edit/' + id, function (data) {
            $('#bidang_id').val(data.id);
            $('#nama_bidang').val(data.nama);
            $('#kode_bidang').val(data.kode);
            $('#ket_bidang').val(data.ket);
            loadProdiBidang(data.prodi); // set selected prodi
            $('.modal-title').text('Edit Bidang Peminatan');
            $('#bidangModal').modal('show');
        });
    });

    // submit form (add/update) bidang
    $('#bidangForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#bidang_id').val();
        var url = id ? '/bidang-peminatan/update/' + id : '/bidang-peminatan/store';
        var method = 'POST';
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#bidangModal').modal('hide');
                    bidangTable.ajax.reload();
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan!');
                console.log(xhr.responseText);
            }
        });
    });

    // delete bidang
    $(document).on('click', '.deleteBidangBtn', function () {
        if (!confirm('Yakin ingin menghapus data ini?')) return;
        var id = $(this).data('id');
        $.ajax({
            url: '/bidang-peminatan/delete/' + id,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function (res) {
                if (res.success) {
                    bidangTable.ajax.reload();
                    alert(res.message);
                }
            },
            error: function (xhr) {
                alert('Terjadi kesalahan!');
                console.log(xhr.responseText);
            }
        });
    });

    function loadProdiBidang(selectedId = null) {
        $.getJSON('/get-prodi', function(res) {
            const $prodi = $('#prodi_bidang');
            let groups = {};
            res.options.forEach(function(item) {
                if (!groups[item.optgroup]) groups[item.optgroup] = [];
                groups[item.optgroup].push(item);
            });
            for (const fakultas in groups) {
                const $group = $('<optgroup>', { label: fakultas });
                groups[fakultas].forEach(function(prodi) {
                    $group.append($('<option>', {
                        value: prodi.value,
                        text: prodi.text,
                        selected: selectedId == prodi.value
                    }));
                });
                $prodi.append($group);
            }
            // Jika pakai TomSelect:
            if ($prodi[0].tomselect) {
                $prodi[0].tomselect.destroy();
            }
            new TomSelect($prodi[0], {
                placeholder: '-- Pilih Prodi --',
                create: false,
                allowEmptyOption: true,
                closeAfterSelect: true
            });
        });
    }

});
</script>
@endpush
