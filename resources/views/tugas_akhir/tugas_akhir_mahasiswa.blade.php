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
      @include('tugas_akhir._card_tugas_akhir', ['ta' => $ta])
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
          <div class="mb-3">
            <label for="judul" class="form-label">Judul Tugas Akhir</label>
            <input type="text" name="judul" id="judul" class="form-control" placeholder="Masukkan judul tugas akhir" required maxlength="255">
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
            <label class="form-label">Latar Belakang Masalah (ringkas)</label>
            <textarea class="form-control" id="latar_belakang" name="latar_belakang" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Permasalahan (ringkas)</label>
            <textarea class="form-control" id="permasalahan" name="permasalahan" rows="3"></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Metode Penelitian (ringkas)</label>
            <textarea class="form-control" id="metode" name="metodess" rows="3"></textarea>
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
  document.getElementById('addBtn').addEventListener('click', function() {
  // Reset form sebelum show modal
  const form = document.querySelector('#usulTAModal form');
  form.reset();
  form.action = '{{ route("tugas-akhir.store") }}';
  form.method = 'POST';
  let methodInput = form.querySelector('input[name="_method"]');
  if (methodInput) methodInput.remove();

  document.getElementById('judul').value = '';
  document.getElementById('selectTypeOpt').value = '';
  document.getElementById('selectBidangOpt').value = '';
  document.getElementById('deskripsi').value = '';
  document.getElementById('latar_belakang').value = '';
  document.getElementById('permasalahan').value = '';
  document.getElementById('metode').value = '';
  document.getElementById('pembimbing').value = '';
  document.getElementById('formFile').value = '';

  var modal = new bootstrap.Modal(document.getElementById('usulTAModal'));
  modal.show();
});

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
  document.getElementById('selectTypeOpt').addEventListener('change', function(e) {
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
  document.getElementById('selectTypeOpt').addEventListener('blur', function() {
    if (popoverInstance) {
      popoverInstance.hide();
    }
  });

  // Ambil data bidang peminatan dari DB
  fetch('/get-bidang-peminatan')
    .then(res => res.json())
    .then(data => {
      const bidangGroup = document.getElementById('bidangPeminatanGroup');
      const select = document.getElementById('selectBidangOpt');
      if (data.length === 0) {
        bidangGroup.style.display = 'none';
      } else {
        bidangGroup.style.display = '';
        select.innerHTML = '<option value="">--Pilih Bidang Peminatan--</option>';
        data.forEach(item => {
          select.innerHTML += `<option value="${item.id}" data-nama="${item.nama}" data-ket="${item.ket ?? ''}">${item.nama}</option>`;
        });
      }
    });

  let bidangPopoverInstance = null;
  document.getElementById('selectBidangOpt').addEventListener('change', function(e) {
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
  document.getElementById('selectBidangOpt').addEventListener('blur', function() {
    if (bidangPopoverInstance) {
      bidangPopoverInstance.hide();
    }
  });

  document.querySelector('#usulTAModal form').addEventListener('submit', function(e) {
    e.preventDefault();
    let form = this;
    let formData = new FormData(form);

    fetch(form.action, {
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

        fetch('/tugas-akhir/last')
          .then(res => res.text())
          .then(html => {
            const noRow = document.getElementById('noTugasAkhirRow');
            if (noRow) noRow.remove();
            document.getElementById('tugasAkhirList').insertAdjacentHTML('afterbegin', html);
          });
      } else {
        alert('Gagal menyimpan data!');
      }
    })
    .catch(() => alert('Terjadi kesalahan!'));
  });

  // Edit Tugas Akhir
  document.querySelectorAll('.editTugasAkhirBtn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var id = this.getAttribute('data-id');
      fetch('/tugas-akhir/' + id + '/edit')
        .then(res => res.json())
        .then(data => {
          const modal = new bootstrap.Modal(document.getElementById('usulTAModal'));
          modal.show();

          document.getElementById('judul').value = data.judul;
          document.getElementById('selectTypeOpt').value = data.jenis_penelitian_id;
          document.getElementById('selectBidangOpt').value = data.bidang_peminatan_id;
          document.getElementById('deskripsi').value = data.deskripsi ?? '';
          document.getElementById('pembimbing').value = data.pembimbing_id ?? '';

          const form = document.querySelector('#usulTAModal form');
          form.action = '/tugas-akhir/' + id + '/update';
          form.method = 'POST';

          let methodInput = form.querySelector('input[name="_method"]');
          if (!methodInput) {
            methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            form.appendChild(methodInput);
          }
          methodInput.value = 'POST';
        });
    });
  });

  // Delete Tugas Akhir
  document.querySelectorAll('.deleteTugasAkhirBtn').forEach(function(btn) {
    btn.addEventListener('click', function() {
      if (!confirm('Yakin ingin menghapus Tugas Akhir ini?')) return;
      var id = this.getAttribute('data-id');
      fetch('/tugas-akhir/' + id, {
        method: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        }
      })
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          btn.closest('.tugas-akhir-card').remove();
          alert(data.message);
        } else {
          alert('Gagal menghapus!');
        }
      });
    });
  });

  document.getElementById('usulTAModal').addEventListener('hidden.bs.modal', function () {
    const form = this.querySelector('form');
    if (form) {
      form.reset();
      form.action = '{{ route("tugas-akhir.store") }}';
      form.method = 'POST';
      // Hapus input _method jika ada
      let methodInput = form.querySelector('input[name="_method"]');
      if (methodInput) methodInput.remove();

      // Reset semua select dan textarea
      document.getElementById('judul').value = '';
      document.getElementById('selectTypeOpt').value = '';
      document.getElementById('selectBidangOpt').value = '';
      document.getElementById('deskripsi').value = '';
      document.getElementById('latar_belakang').value = '';
      document.getElementById('permasalahan').value = '';
      document.getElementById('metode').value = '';
      document.getElementById('pembimbing').value = '';
      document.getElementById('formFile').value = '';
    }
  });
</script>
@endpush
@endsection

