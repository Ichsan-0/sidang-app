@extends('layout.app')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Informasi Tugas Akhir</h4>
    <button class="btn btn-primary" id="addBtn">
      <i class="bx bx-plus"></i> Usul Tugas Akhir
    </button>
  </div>
  <div class="row mb-4">
    <div class="col-md-12 col-xl-12">
      <div class="card bg-secondary text-white shadow-sm">
        <div class="card-body">
          <p class="card-text mb-0">Belum ada Tugas Akhir yang diusulkan</p>
        </div>
      </div>
    </div>
  </div>
</div>
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
            <select id="selectTypeOpt" class="form-select" name="type" style="flex: 1;">
            </select>
          </div>
          
        </div>
        
        <div class="mb-3">
          <label for="selectBidangOpt" class="form-label">Bidang Peminatan</label>
          <div class="d-flex gap-2 align-items-center">
            <select id="selectBidangOpt" class="form-select" name="bidang" style="flex: 1;">
            </select>
          </div>
        </div>
        <div class="mb-3">
          <label for="formFile" class="form-label">Upload Draft/Lampiran</label>
          <input class="form-control" type="file" id="formFile" name="draft">
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
          <label for="deskripsi" class="form-label">Deskripsi / Catatan</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
        </div>
        <div class="mb-3">
          <label for="pembimbing" class="form-label">Pembimbing</label>
          <input type="text" class="form-control" id="pembimbing" name="pembimbing" placeholder="Nama Pembimbing" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Kirim Usulan</button>
      </div>
    </form>
  </div>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

@endpush

@push('scripts')
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
      const select = document.getElementById('selectBidangOpt');
      select.innerHTML = '<option value="">--Pilih Bidang Peminatan--</option>';
      data.forEach(item => {
        select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
        bidangPeminatanData[item.id] = item;
      });
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
});
</script>
@endpush
@endsection

