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
              <th>Judul Dituliskan</th>
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
      { data: 'status', name: 'status', orderable: false, searchable: false },
      { data: 'action', name: 'action', searchable: false }
    ]
  });

  $('#TugasAkhir').on('click', '.detailBtn', function() {
    const mahasiswaId = $(this).data('id');
    const namaMahasiswa = $(this).closest('tr').find('td:eq(1)').text(); // kolom ke-2 adalah nama mahasiswa
    const nimMahasiswa = $(this).closest('tr').find('td:eq(2)').text(); // kolom ke-3 adalah NIM

    $.get('/tugas-akhir/detail/' + mahasiswaId, function(html) {
      $('#detailTAContent').html(html);
      $('#detailTAModalLabel').text('Detail Usulan Tugas Akhir - ' + namaMahasiswa + ' (' + nimMahasiswa + ')');

       
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

      // Inisialisasi Quill untuk editor dengan class .quill-editor-adminprodi
      $('#detailTAContent').find('.quill-editor-adminprodi').each(function() {
        const editorId = $(this).attr('id');
        const inputId = editorId + '-input';
        const quill = new Quill('#' + editorId, { theme: 'snow' });
        $(this).closest('form').on('submit', function() {
          document.getElementById(inputId).value = quill.root.innerHTML;
        });
      });

      $('#detailTAContent').find('.form-update-ta').each(function() {
        $(this).on('submit', function(e) {
          e.preventDefault();
          const form = this;
          const formData = new FormData(form);

          // Simpan id tab aktif sebelum reload
          const activeTabId = $('#detailTAContent .nav-link.active').attr('id');

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
              // Refresh list usulan di background
              $.get('/tugas-akhir/all', function(listHtml) {
                $('#tugasAkhirList').html(listHtml);
              });
              // Ambil data detail terbaru dan update isi modal
              $.get('/tugas-akhir/detail/' + mahasiswaId, function(html) {
                $('#detailTAContent').html(html);

                // Aktifkan kembali tab yang sebelumnya aktif
                if (activeTabId) {
                  setTimeout(function() {
                    $('#' + activeTabId).tab('show');
                  }, 100);
                }

                // Inisialisasi ulang Quill editor
                $('#detailTAContent').find('.quill-editor').each(function() {
                  const editorId = $(this).attr('id');
                  const inputId = editorId.replace('editor', 'input');
                  const quill = new Quill('#' + editorId, { theme: 'snow' });
                  $(this).closest('form').on('submit', function() {
                    document.getElementById(inputId).value = quill.root.innerHTML;
                  });
                });

                // Inisialisasi ulang Quill untuk editor dengan class .quill-editor-adminprodi
                $('#detailTAContent').find('.quill-editor-adminprodi').each(function() {
                  const editorId = $(this).attr('id');
                  const inputId = editorId + '-input';
                  const quill = new Quill('#' + editorId, { theme: 'snow' });
                  $(this).closest('form').on('submit', function() {
                    document.getElementById(inputId).value = quill.root.innerHTML;
                  });
                });

                // Inisialisasi ulang select status
                $('#detailTAContent').find('.status-select').on('change', function() {
                  const taid = $(this).data('taid');
                  const label = document.getElementById('catatan-label-' + taid);
                  const val = $(this).val();
                  if (val == '3') {
                    label.innerHTML = '<strong>Alasan Penolakan:</strong>';
                  } else if (val == '2') {
                    label.innerHTML = '<strong>Catatan / Revisi Pembimbing:</strong>';
                  } else {
                    label.innerHTML = '<strong>Catatan </strong>';
                  }
                });

                $('#detailTAContent').find('.status-select').each(function() {
                  const selectedValue = $(this).attr('data-selected');
                  if (selectedValue) $(this).val(selectedValue);
                });
              });
            } else {
              alert(data.message || 'Gagal update!');
            }
          })
          .catch(() => alert('Terjadi kesalahan!'));
        });
      });

      // Live update label catatan/revisi saat status select berubah
      $('#detailTAContent').find('.status-select').on('change', function() {
        const taid = $(this).data('taid');
        const label = document.getElementById('catatan-label-' + taid);
        const val = $(this).val();
        if (val == '3') {
            label.innerHTML = '<strong>Alasan Penolakan:</strong>';
          } else if (val == '2') {
            label.innerHTML = '<strong>Catatan / Revisi Pembimbing:</strong>';
          } else {
            label.innerHTML = '<strong>Catatan </strong>';
          }
        });

      $('#detailTAContent').find('.status-select').each(function() {
        const selectedValue = $(this).attr('data-selected');
        if (selectedValue) $(this).val(selectedValue);
      });

      var modal = new bootstrap.Modal(document.getElementById('detailTAModal'));
      modal.show();
      var modalEl = document.getElementById('detailTAModal');
      modalEl.addEventListener('hidden.bs.modal', function () {
        $('#TugasAkhir').DataTable().ajax.reload(null, false);
      });
    });
  });

  $(document).on('click', '[id^=SkBtn-]', function() {
    var taId = $(this).attr('id').replace('SkBtn-', '');
    $.post("{{ route('sk-proposal.create') }}", {
        tugas_akhir_id: taId,
        _token: "{{ csrf_token() }}"
    }, function(res) {
        if(res.success) {
            alert('SK Proposal berhasil dibuat!');
            // reload atau update tampilan jika perlu
        }
    });
});
});

</script>

@endpush
@endsection
