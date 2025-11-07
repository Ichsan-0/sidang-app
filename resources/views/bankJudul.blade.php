@extends('layout.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Bank Judul</h4>
    </div>
    <ul class="nav nav-pills mb-3" role="tablist">
        <li class="nav-item">
        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-bank-judul" aria-controls="navs-justified-home" aria-selected="true">
            <i class="tf-icons bx bx-home"></i> Bank Judul
        </button>
        </li>
        <li class="nav-item">
        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-rekomendasi-judul" aria-controls="navs-rekomendasi-judul" aria-selected="false">
            <i class="tf-icons bx bx-bulb"></i> Rekomendasi Judul
        </button>
        </li>
    </ul>
    <div class="tab-content">
        <!-- TAB: BANK JUDUL -->
        <div class="tab-pane fade show active" id="navs-bank-judul" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Bank Judul</h5>
                    @hasanyrole('admin prodi|superadmin')
                    <div class="d-flex gap-2">
                        <button class="btn btn-primary" id="btnAddBankJudul">
                            <i class="bx bx-plus"></i> Judul
                        </button>
                        <button class="btn btn-success" id="btnuploadExcel">
                            <i class="bx bx-upload"></i> Upload Excel
                        </button>
                    </div>
                    @endhasanyrole
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="tableBankJudul" class="table table-hover">
                          <thead class="table-light">
                            <tr>
                              <th class="col-no">No</th> 
                              <th>Judul</th>
                              <th>Deskripsi</th>
                              <th>Status</th>
                              <th>Dibuat</th>
                              @can('bank-judul-edit')
                              <th>Aksi</th>
                              @endcan
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- TAB: REKOMENDASI JUDUL -->
        <div class="tab-pane fade" id="navs-rekomendasi-judul" role="tabpanel">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Rekomendasi Judul</h5>
                    @hasanyrole('dosen|superadmin')
                    <button class="btn btn-primary" id="btnAddRekomendasi">
                        <i class="bx bx-plus"></i> Rekomendasi
                    </button>
                    @endhasanyrole
                </div>
                <div class="card-body">
                    <div class="table-responsive text-nowrap">
                        <table id="tableRekomendasiJudul" class="table table-hover">
                          <thead class="table-light">
                            <tr>
                              <th class="col-no">No</th>            <!-- tambahkan class -->
                              <th>Judul</th>
                              <th>Topik</th>
                              <th>Status</th>
                              <th>Dibuat</th>
                              <th>Aksi</th>
                            </tr>
                          </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Judul -->
<div class="modal fade" id="judulModal" tabindex="-1" aria-labelledby="judulModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulModalLabel">Tambah Judul</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formBankJudul">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" id="judul_id">
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="1">Aktif</option>
                            <option value="2">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Rekomendasi -->
<div class="modal fade" id="rekomendasiModal" tabindex="-1" aria-labelledby="rekomendasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rekomendasiModalLabel">Tambah Rekomendasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="formRekomendasiJudul">
        @csrf
        <div class="modal-body">
          <input type="hidden" name="id" id="rekom_id">
          <div class="mb-3">
            <label class="form-label" for="rekom_judul">Judul</label>
            <input type="text" name="judul" id="rekom_judul" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label" for="rekom_deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="rekom_deskripsi" class="form-control" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label" for="rekom_status">Status</label>
            <select name="status" id="rekom_status" class="form-control" required>
              <option value="1">Aktif</option>
              <option value="2">Nonaktif</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
<style>
  /* desktop/default */
  .col-judul { white-space: normal; }

  /* samakan lebar kolom No (th + td) di semua tabel */
  #tableBankJudul th.col-no, #tableBankJudul td.col-no,
  #tableRekomendasiJudul th.col-no, #tableRekomendasiJudul td.col-no {
    width: 42px;
    max-width: 42px;
    white-space: nowrap;
  }

  @media (max-width: 576px) {
    #tableBankJudul, #tableRekomendasiJudul { font-size: 12px; }
    #tableBankJudul th, #tableBankJudul td,
    #tableRekomendasiJudul th, #tableRekomendasiJudul td { padding: .35rem .5rem; }

    #tableBankJudul td.col-judul, #tableRekomendasiJudul td.col-judul {
      min-width: 180px; white-space: normal; overflow-wrap: anywhere;
    }
  }
</style>
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script>
$(function () {
  const table = $('#tableBankJudul').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/bank-judul/ajax',
    autoWidth: false,                     
    responsive: { details:{ type:'row', target:'tr' }, breakpoints:[
      { name:'mobile', width:576 }, { name:'desktop', width:Infinity }
    ]},
    columnDefs: [
      { targets: 0, width: '42px', className: 'col-no' }, // paksa lebar kolom No
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 0 },
      { responsivePriority: 3, targets: -1 }
    ],
    columns: [
      { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
      { data:'judul', name:'judul', className:'col-judul' },
      { data:'deskripsi', name:'deskripsi' },
      { data:'status', name:'status' },
      { data:'nama', name:'nama' },
      @can('bank-judul-edit')
      { data:'action', name:'action', orderable:false, searchable:false }
      @endcan
    ]
  });

  const tableRekom = $('#tableRekomendasiJudul').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/bank-judul/rekomendasi/ajax',
    autoWidth: false,                          // konsisten dengan tabel pertama
    responsive: { details:{ type:'row', target:'tr' }, breakpoints:[
      { name:'mobile', width:576 }, { name:'desktop', width:Infinity }
    ]},
    columnDefs: [
      { targets: 0, width: '42px', className: 'col-no' }, // paksa lebar kolom No
      { responsivePriority: 1, targets: 1 },
      { responsivePriority: 2, targets: 0 },
      { responsivePriority: 3, targets: -1 }
    ],
    columns: [
      { data:'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false },
      { data:'judul', name:'judul', className:'col-judul' },
      { data:'topik', name:'topik' },
      { data:'status', name:'status' },
      { data:'created_at', name:'created_at' },
      { data:'action', name:'action', orderable:false, searchable:false }
    ]
  });

  // Handle Add Button Click
  $('#btnAddBankJudul').click(function() {
    $('#judulModalLabel').text('Tambah Judul');
    $('#judul_id').val('');
    $('#formBankJudul')[0].reset();
    $('#judulModal').modal('show');
  });

  // Handle Form Submit
  $('#formBankJudul').on('submit', function(e) {
    e.preventDefault();
    let id = $('#judul_id').val();
    let url = '/bank-judul' + (id ? '/' + id : '');
    let method = id ? 'PUT' : 'POST';

    $.ajax({
      url: url,
      method: method,
      data: $(this).serialize(),
      success: function(res) {
        if(res.success) {
          $('#judulModal').modal('hide');
          table.ajax.reload();
          alert(id ? 'Judul berhasil diperbarui' : 'Judul berhasil ditambahkan');
        }
      },
      error: function(xhr) {
        if(xhr.status === 422) {
          let errors = xhr.responseJSON.errors;
          alert(Object.values(errors).flat().join('\n'));
        } else {
          alert('Terjadi kesalahan');
        }
      }
    });
  });

  // Handle Edit Button Click
  $(document).on('click', '.btnEdit', function() {
    let id = $(this).data('id');
    $('#judulModalLabel').text('Edit Judul');

    // Get judul data
    $.ajax({
      url: '/bank-judul/list',
      data: { id: id },
      success: function(res) {
        $('#judul_id').val(res.id);
        $('#judul').val(res.judul);
        $('#deskripsi').val(res.deskripsi);
        $('#status').val(res.status);
        $('#judulModal').modal('show');
      }
    });
  });

  // Handle Delete Button Click
  $(document).on('click', '.btnDelete', function() {
    if (confirm('Yakin hapus judul ini?')) {
      let id = $(this).data('id');
      $.ajax({
        url: '/bank-judul/' + id,
        method: 'DELETE',
        data: {_token: "{{ csrf_token() }}"},
        success: function(res) {
          if(res.success) {
            table.ajax.reload();
            alert('Judul berhasil dihapus');
          }
        },
        error: function() {
          alert('Gagal menghapus judul');
        }
      });
    }
  });

  // Set CSRF header untuk semua AJAX
  $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }});

  // Buka modal Tambah Rekomendasi
  $('#btnAddRekomendasi').on('click', function () {
    $('#rekomendasiModalLabel').text('Tambah Rekomendasi');
    $('#rekom_id').val('');
    $('#formRekomendasiJudul')[0].reset();
    $('#rekom_status').val('1');
    $('#rekomendasiModal').modal('show');
  });

  // Submit (Create / Update) Rekomendasi
  $('#formRekomendasiJudul').on('submit', function (e) {
    e.preventDefault();
    const id = $('#rekom_id').val();
    const url = '/bank-judul/rekomendasi' + (id ? '/' + id : '');
    const method = id ? 'PUT' : 'POST';

    $.ajax({
      url, method,
      data: $(this).serialize(),
      success: function (res) {
        if (res.success) {
          $('#rekomendasiModal').modal('hide');
          tableRekom.ajax.reload(null, false);
          alert(id ? 'Rekomendasi diperbarui' : 'Rekomendasi ditambahkan');
        }
      },
      error: function (xhr) {
        if (xhr.status === 422) {
          const errors = xhr.responseJSON.errors;
          alert(Object.values(errors).flat().join('\n'));
        } else {
          alert('Gagal menyimpan data');
        }
      }
    });
  });

  // Edit Rekomendasi
  $(document).on('click', '.btnEditRekom', function () {
    const id = $(this).data('id');
    $('#rekomendasiModalLabel').text('Edit Rekomendasi');
    $.get('/bank-judul/rekomendasi/find', { id }, function (res) {
      $('#rekom_id').val(res.id);
      $('#rekom_judul').val(res.judul);
      $('#rekom_deskripsi').val(res.deskripsi);
      $('#rekom_status').val(res.status);
      $('#rekomendasiModal').modal('show');
    });
  });

  // Hapus Rekomendasi
  $(document).on('click', '.btnDeleteRekom', function () {
    if (!confirm('Hapus rekomendasi ini?')) return;
    const id = $(this).data('id');
    $.ajax({
      url: '/bank-judul/rekomendasi/' + id,
      method: 'DELETE',
      data: { _token: '{{ csrf_token() }}' },
      success: function (res) {
        if (res.success) {
          tableRekom.ajax.reload(null, false);
          alert('Rekomendasi dihapus');
        }
      },
      error: function () { alert('Gagal menghapus'); }
    });
  });

  // Recalc kolom saat tab dibuka/resize agar behavior breakpoint akurat
  $('button[data-bs-target="#navs-rekomendasi-judul"]').on('shown.bs.tab', function () {
    tableRekom.columns.adjust().responsive.recalc();
  });
  $('button[data-bs-target="#navs-bank-judul"]').on('shown.bs.tab', function () {
    table.columns.adjust().responsive.recalc();
  });
  $(window).on('resize', function () {
    table.columns.adjust().responsive.recalc();
    tableRekom.columns.adjust().responsive.recalc();
  });
});
</script>
@endpush
