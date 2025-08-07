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
              <th>Kode Prodi</th>
              <th>Fakultas</th>
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
    <form id="prodiForm" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="id" id="prodi_id">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="nama_prodi" class="form-label">Nama Prodi</label>
            <input type="text" class="form-control" name="nama_prodi" id="nama_prodi" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Kode Prodi</label>
            <input type="text" class="form-control" name="kode_prodi" id="kode_prodi" required>
          </div>
          <div class="mb-4">
            <label for="exampleFormControlSelect1" class="form-label">Pilih Fakultas</label>
            <select class="form-select" name="id_fakultas" id="id_fakultas" required>
                <option value="">-- Pilih Fakultas --</option>
                @foreach($fakultas as $f)
                  <option value="{{ $f->id }}">{{ $f->kode }} ({{ $f->nama }})</option>
                @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea class="form-control" name="ket" required> </textarea>
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label">Upload Template Pengajuan Judul</label>
            <input class="form-control" type="file" id="formFile" name="draft" accept=".doc,.docx,.pdf">
            <div id="filePreview" class="mt-2"></div>
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label">Upload Template Panduan Skripsi</label>
            <input class="form-control" type="file" id="formFile" name="panduan" accept=".doc,.docx,.pdf">
            <div id="filePreview" class="mt-2"></div>
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
    var table = $('#prodiTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("prodi.ajax") }}',
        autoWidth: false, 
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'nama_prodi', name: 'nama_prodi' },
            { data: 'kode_prodi', name: 'kode_prodi' },
            { data: 'nama_fakultas', name: 'nama_fakultas', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // show modal for add
    $('#addBtn').on('click', function () {
        $('#prodiForm')[0].reset();
        $('#prodi_id').val('');
        $('#filePreview').html(''); // <-- tambahkan baris ini
        $('.modal-title').text('Tambah Prodi');
        $('#prodiModal').modal('show');
    });

    // show modal for edit
    $(document).on('click', '.editBtn', function () {
        var id = $(this).data('id');
        $.get('/prodi/edit/' + id, function (data) {
            $('#prodi_id').val(data.id);
            $('#nama_prodi').val(data.nama_prodi);
            $('#kode_prodi').val(data.kode_prodi);
            $('#id_fakultas').val(data.id_fakultas);
            $('#ket').val(data.ket);
            // Preview file jika ada
            if (data.draft) {
                let url = '/storage/' + data.draft;
                let ext = url.split('.').pop().toLowerCase();
                let html = '';
                if (ext === 'pdf') {
                    html = `<a href="${url}" target="_blank" class="btn btn-sm btn-info">Lihat File (PDF)</a>`;
                } else if (ext === 'doc' || ext === 'docx') {
                    html = `<a href="${url}" target="_blank" class="btn btn-sm btn-info">Download File (Word)</a>`;
                }
                $('#filePreview').html(html);
            } else {
                $('#filePreview').html('');
            }
            $('.modal-title').text('Edit Prodi');
            $('#prodiModal').modal('show');
        });
    });

    // submit form (add/update)
    $('#prodiForm').on('submit', function (e) {
        e.preventDefault();
        var id = $('#prodi_id').val();
        var url = id ? '/prodi/update/' + id : '/prodi/store';
        var method = 'POST';

        var formData = new FormData(this);

        $.ajax({
            url: url,
            method: method,
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.success) {
                    $('#prodiModal').modal('hide');
                    table.ajax.reload();
                    $('#formFile').val(''); // <-- reset input file
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
            url: '/prodi/delete/' + id,
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
