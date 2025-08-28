@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Informasi Tugas Akhir</h4>
    @if(auth()->user()->hasRole('mahasiswa'))
      <button class="btn btn-primary"
          id="addBtn"
          {{-- @if($tugasAkhir->count() > 0) disabled @endif --}}
      >
        <i class="bx bx-plus"></i> Usul Tugas Akhir
      </button>
    @endif
  </div>
  
  @if(auth()->user()->hasRole('mahasiswa'))
  <div class="row" id="tugasAkhirList">
    @forelse($tugasAkhir as $ta)
      @include('layout._card_tugas_akhir', ['ta' => $ta])
    @empty
      <div class="col-12" id="noTugasAkhirRow">
        <div class="alert alert-primary alert-dismissible" role="alert">
          <strong>Perhatian:</strong> Pastikan untuk mengisi semua informasi yang diperlukan sebelum mengirimkan usulan Tugas Akhir.
          <br>
          Jika ada pertanyaan, silakan hubungi dosen pembimbing atau koordinator program studi.
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <div class="card bg-secondary text-white shadow-sm">
          <div class="card-body">
            <p class="card-text mb-0">Belum ada Tugas Akhir yang diusulkan</p>
          </div>
        </div>
      </div>
    @endforelse
  </div>
  @endif
  @if(!auth()->user()->hasRole('mahasiswa'))
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
              <th>Status</th>
              <th>Nama Mahasiswa</th>
              <th>Judul Diajukan</th>
              <th>Jenis Tugas Akhir</th>
              <th>Bidang Peminatan</th>
              
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
  @endif
<!-- Modal Usul Tugas Akhir -->
  <div class="modal fade" id="usulTAModal" tabindex="-1" aria-labelledby="usulTAModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="#" method="POST" class="modal-content" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title" id="usulTAModalLabel">Pengusulan Tugas Akhir</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 input-judul">
            <div class="judul-group mb-2">
              <label class="form-label">Judul Tugas Akhir Pertama</label>
              <div class="d-flex gap-2 align-items-center">
                <input type="text" class="form-control" name="judul[]" placeholder="Masukkan usulan judul tugas akhir" required>
                <button type="button" class="btn btn-icon btn-primary" id="addJudulBtn" title="Tambah Judul yang diajukan">
                  <i class="bx bx-plus"></i>
                </button>
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="selectTypeOpt" class="form-label">Jenis Tugas Akhir</label>
            <div class="d-flex gap-2 align-items-center">
              <select id="selectTypeOpt" class="form-select" name="jenis_penelitian_id" style="flex: 1;">
              </select>
            </div>
            
          </div>
          
          <div class="mb-3" id="bidangPeminatanGroup">
            <label for="selectBidangOpt" class="form-label">Bidang Peminatan</label>
            <div class="d-flex gap-2 align-items-center">
              <select id="selectBidangOpt" class="form-select" name="bidang_peminatan_id" style="flex: 1;">
              </select>
            </div>
          </div>
          <div class="mb-3">
            <label for="formFile" class="form-label">Upload Draft/Lampiran</label>
            <input class="form-control" type="file" id="formFile" name="file">
            <div class="form-text">
              Upload file draft atau lampiran yang relevan.
              @if(isset($prodi) && $prodi->draft)
                <a href="{{ asset('storage/'.$prodi->draft) }}" target="_blank" class="badge bg-primary text-white" style="cursor:pointer;">
                  lihat template
                </a>
              @else
                <span class="badge bg-secondary text-white" style="cursor:not-allowed;">template belum tersedia</span>
              @endif
            </div>
          </div>
          
          <div class="mb-3">
            <label for="deskripsi" class="form-label">Catatan Usulan :</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
          </div>
          <div class="mb-3">
            <label for="pembimbing" class="form-label">Pembimbing</label>
            <select class="form-select" id="pembimbing" name="pembimbing_id" required>
              <option value="">-- Pilih Pembimbing --</option>
              @foreach($dosenList as $dosen)
                <option value="{{ $dosen->id }}">{{ $dosen->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Kirim Usulan</button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(function () {
  @if(!auth()->user()->hasRole('mahasiswa'))
  $('#TugasAkhir').DataTable({
    processing: true,
    serverSide: true,
    ajax: '{{ route("tugas-akhir.ajax") }}',
    columns: [
      { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
      { data: 'status', name: 'status', orderable: true},
      { data: 'nama_mahasiswa', name: 'nama_mahasiswa', orderable: false, searchable: false },
      { data: 'jumlah_judul', name: 'jumlah_judul', orderable: false, searchable: false },
      { data: 'jenis_tugas_akhir', name: 'jenis_tugas_akhir', orderable: false, searchable: false },
      { data: 'bidang_peminatan', name: 'bidang_peminatan', orderable: false, searchable: false },
    ]
  });
  @endif
});
</script>
<script>
  document.getElementById('addBtn').addEventListener('click', function() {
    var modal = new bootstrap.Modal(document.getElementById('usulTAModal'));
    modal.show();
  });

  document.addEventListener('DOMContentLoaded', function () {
  const addJudulBtn = document.getElementById('addJudulBtn');
  const inputContainer = document.querySelector('.input-judul');

  const labelList = [
    "Judul Tugas Akhir Pertama",
    "Judul Tugas Akhir Kedua",
    "Judul Tugas Akhir Ketiga",
    "Judul Tugas Akhir Keempat",
    "Judul Tugas Akhir Kelima"
  ];

  function updateLabels() {
    const inputGroups = inputContainer.querySelectorAll('.judul-group');
    inputGroups.forEach((group, index) => {
      const label = group.querySelector('label');
      label.textContent = labelList[index] || `Judul Tugas Akhir Ke-${index + 1}`;
    });
  }

  addJudulBtn.addEventListener('click', function () {
    const total = inputContainer.querySelectorAll('.judul-group').length;
    const inputGroup = document.createElement('div');
    inputGroup.classList.add('judul-group', 'mb-2');

    const label = document.createElement('label');
    label.classList.add('form-label');

    const row = document.createElement('div');
    row.classList.add('d-flex', 'gap-2', 'align-items-center');

    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'judul[]';
    input.classList.add('form-control');
    input.placeholder = 'Masukkan Usulan Judul Tugas Akhir';
    input.required = true;

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.classList.add('btn', 'btn-icon', 'btn-danger');
    removeBtn.title = 'Hapus judul ini';
    removeBtn.innerHTML = '<i class="bx bx-minus"></i>';

    removeBtn.addEventListener('click', function () {
      inputContainer.removeChild(inputGroup);
      updateLabels();
    });

    row.appendChild(input);
    row.appendChild(removeBtn);
    inputGroup.appendChild(label);
    inputGroup.appendChild(row);

    inputContainer.appendChild(inputGroup);
    updateLabels();
  });

  // Inisialisasi label pertama
  updateLabels();

  // Ambil data jenis penelitian dari DB
  let jenisPenelitianData = {};
  fetch('/get-jenis-penelitian')
    .then(res => res.json())
    .then(data => {
      const select = document.getElementById('selectTypeOpt');
      select.innerHTML = '<option value="">--Pilih Jenis Tugas Akhir--</option>';
      data.forEach(item => {
        select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
        jenisPenelitianData[item.id] = item;
      });
    });

  // Popover instance
  let popoverInstance = null;

  // Tampilkan popover saat select diubah
  document.getElementById('selectTypeOpt').addEventListener('change', function(e) {
    // Hapus popover lama jika ada
    if (popoverInstance) {
      popoverInstance.dispose();
      popoverInstance = null;
    }
    const selected = this.options[this.selectedIndex];
    const nama = selected.getAttribute('data-nama');
    const ket = selected.getAttribute('data-ket');
    if (this.value && nama) {
      popoverInstance = new bootstrap.Popover(this, {
        title: nama,
        content: ket || 'Tidak ada deskripsi.',
        placement: 'right',
        trigger: 'manual',
        html: true
      });
      popoverInstance.show();
    }
  });

  // Sembunyikan popover jika select kehilangan fokus
  document.getElementById('selectTypeOpt').addEventListener('blur', function() {
    if (popoverInstance) {
      popoverInstance.hide();
    }
  });

  // Ambil data bidang peminatan dari DB
  let bidangPeminatanData = {};
  fetch('/get-bidang-peminatan')
    .then(res => res.json())
    .then(data => {
      const bidangGroup = document.getElementById('bidangPeminatanGroup');
      const select = document.getElementById('selectBidangOpt');
      if (data.length === 0) {
        bidangGroup.style.display = 'none'; // hide seluruh blok
      } else {
        bidangGroup.style.display = '';
        select.innerHTML = '<option value="">--Pilih Bidang Peminatan--</option>';
        data.forEach(item => {
          select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
        });
      }
    });

  // Popover instance untuk bidang peminatan
  let bidangPopoverInstance = null;

  // Tampilkan popover saat select bidang diubah
  document.getElementById('selectBidangOpt').addEventListener('change', function(e) {
    // Hapus popover lama jika ada
    if (bidangPopoverInstance) {
      bidangPopoverInstance.dispose();
      bidangPopoverInstance = null;
    }
    const selected = this.options[this.selectedIndex];
    const nama = selected.getAttribute('data-nama');
    const ket = selected.getAttribute('data-ket');
    if (this.value && nama) {
      bidangPopoverInstance = new bootstrap.Popover(this, {
        title: nama,
        content: ket || 'Tidak ada deskripsi.',
        placement: 'right',
        trigger: 'manual',
        html: true
      });
      bidangPopoverInstance.show();
    }
  });

  // Sembunyikan popover jika select kehilangan fokus
  document.getElementById('selectBidangOpt').addEventListener('blur', function() {
    if (bidangPopoverInstance) {
      bidangPopoverInstance.hide();
    }
  });

  document.querySelector('#usulTAModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);

    fetch('{{ route("tugas-akhir.store") }}', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        form.reset();
        bootstrap.Modal.getInstance(document.getElementById('usulTAModal')).hide();
        alert(data.message);

        // Ambil data tugas akhir terbaru dari backend (misal endpoint /tugas-akhir/last)
        fetch('/tugas-akhir/last')
          .then(res => res.text())
          .then(html => {
            // Hilangkan pesan "Belum ada Tugas Akhir"
            const noRow = document.getElementById('noTugasAkhirRow');
            if (noRow) noRow.remove();

            // Tambahkan card baru ke list
            document.getElementById('tugasAkhirList').insertAdjacentHTML('afterbegin', html);
          });
      } else {
        alert('Gagal menyimpan data!');
      }
    })
    .catch(() => alert('Terjadi kesalahan!'));
  });
});
</script>
@endpush
@endsection

