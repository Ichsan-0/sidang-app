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
              <th>Nama Mahasiswa</th>
              <th>NIM</th>
              <th>Judul Diajukan</th>
              <th>Prodi</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
  <!-- Modal Detail Tugas Akhir -->
  <div class="modal fade" id="detailTAModal" tabindex="-1" aria-labelledby="detailTAModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <div>
        <h5 class="modal-title text-primary" id="detailTAModalLabel"></h5>
        <small class="text-muted d-block mt-1">Pastikan untuk memperbarui status pemeriksaan usulan judul secara tepat agar proses administrasi berjalan optimal.</small>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div id="detailTAContent">
        <!-- Konten detail akan dimunculkan di sini via JS -->
          </div>
        </div>
      </div>
    </div>
  </div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
$(function () {
  $('#TugasAkhir').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("tugas-akhir.ajax") }}',
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'nama_mahasiswa', name: 'users.name' },
      { data: 'nim_mahasiswa', name: 'users.no_induk' },
      { data: 'jumlah_judul', name: 'jumlah_judul', searchable: false },
      { data: 'kode_prodi', name: 'kode_prodi', orderable: false, searchable: false },
      { data: 'status', name: 'status', orderable: true, searchable: false },
      { data: 'action', name: 'action', orderable: false, searchable: false }
    ]
  });

  $('#TugasAkhir').on('click', '.detailBtn', function() {
    const mahasiswaId = $(this).data('id');
    const namaMahasiswa = $(this).closest('tr').find('td:eq(1)').text(); // kolom ke-2 adalah nama mahasiswa
    const nimMahasiswa = $(this).closest('tr').find('td:eq(2)').text(); // kolom ke-3 adalah NIM

    $.get('/tugas-akhir/detail/' + mahasiswaId, function(html) {
      $('#detailTAContent').html(html);
      $('#detailTAModalLabel').text('Detail Usulan Tugas Akhir - ' + namaMahasiswa + ' (' + nimMahasiswa + ')');

      // Untuk setiap tab judul
      $('#detailTAContent').find('.tab-pane').each(function() {
        const taId = $(this).attr('id').replace('content-', '');
        const selectTypeId = 'selectTypeOpt-' + taId;
        const selectBidangId = 'selectBidangOpt-' + taId;

        // Isi select jenis penelitian dan inisialisasi popover
        getJenisPenelitian(selectTypeId, function() {
          initSelectPopover(selectTypeId);
          // Ambil value dari atribut data-value
          const selectTypeOpt = document.getElementById(selectTypeId);
          const selectedValue = selectTypeOpt.getAttribute('data-value');
          if (selectedValue) selectTypeOpt.value = selectedValue;
        }.bind(this));

        // Isi select bidang peminatan dan inisialisasi popover
        getBidangPeminatan(selectBidangId, 'bidangPeminatanGroup', function() {
          initBidangPopover(selectBidangId);
          const selectBidangOpt = document.getElementById(selectBidangId);
          const selectedValue = selectBidangOpt.getAttribute('data-value');
          if (selectedValue) selectBidangOpt.value = selectedValue;
        });
      });

      // Inisialisasi Quill untuk setiap catatan
      $('#detailTAContent').find('.quill-editor').each(function() {
        const editorId = $(this).attr('id');
        const inputId = editorId.replace('editor', 'input');
        const quill = new Quill('#' + editorId, { theme: 'snow' });
        // Set value ke input hidden saat form submit
        $(this).closest('form').on('submit', function() {
          document.getElementById(inputId).value = quill.root.innerHTML;
        });
      });

      // Tampilkan/hide catatan sesuai status
      $('#detailTAContent').find('select[name^="status"]').on('change', function() {
        const val = $(this).val();
        const parent = $(this).closest('.mb-3').next('.mb-3');
        if (val == 2 || val == 4) {
          parent.show();
          parent.find('label').text(val == 2 ? 'Catatan Revisi' : 'Catatan Penolakan');
        } else {
          parent.hide();
        }
      }).trigger('change');

      $('#detailTAContent').find('.form-update-ta').each(function() {
        $(this).on('submit', function(e) {
          e.preventDefault();
          const form = this;
          const formData = new FormData(form);

          // Set value Quill ke input hidden
          $(form).find('.quill-editor').each(function() {
            const editorId = $(this).attr('id');
            const inputId = editorId.replace('editor', 'input');
            const quill = Quill.find($('#' + editorId)[0]);
            if (quill) {
              $('#' + inputId).val(quill.root.innerHTML);
            }
          });

          fetch(form.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': $(form).find('input[name="_token"]').val()
            },
            body: formData
          })
          .then(res => res.json())
          .then(data => {
            if (data.success) {
              alert(data.message);
              $('#TugasAkhir').DataTable().ajax.reload(null, false);
            } else {
              alert(data.message || 'Gagal update!');
            }
          })
          .catch(() => alert('Terjadi kesalahan!'));
        });
      });

      var modal = new bootstrap.Modal(document.getElementById('detailTAModal'));
      modal.show();
    });
  });
});

</script>

@endpush
@endsection

