<div class="nav-align-top">
  <ul class="nav nav-pills mb-0" id="taTab" role="tablist">
    @foreach($usulan as $index => $ta)
      <li class="nav-item" role="presentation">
        <button
          type="button"
          class="nav-link {{ $index == 0 ? 'active' : '' }}"
          id="tab-{{ $ta->id }}"
          data-bs-toggle="tab"
          data-bs-target="#content-{{ $ta->id }}"
          aria-controls="content-{{ $ta->id }}"
          aria-selected="{{ $index == 0 ? 'true' : 'false' }}"
          {{ $index == 0 ? '' : 'tabindex="-1"' }}
          role="tab"
        >
          Usulan {{ $index+1 }}
        </button>
      </li>
    @endforeach
  </ul>
  <div class="tab-content" id="taTabContent">
    @foreach($usulan as $index => $ta)
      <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}" id="content-{{ $ta->id }}" role="tabpanel" aria-labelledby="tab-{{ $ta->id }}">
        @php
          $canEdit = isset($editableIds) && in_array($ta->id, $editableIds);
          $statusAkhir = $ta->status->first(); // hasil query di controller: status terakhir
          $activeRole = session('active_role', Auth::user()->getRoleNames()->first());
          $isDosen = $activeRole == 'dosen';
          $isadminProdi = in_array($activeRole, ['admin prodi', 'superadmin', 'pimpinan']);
        @endphp

        <form id="form-update-{{ $ta->id }}" action="{{ route('tugas-akhir.revisi', $ta->id) }}" method="POST" class="form-update-ta">
          @csrf
          @method('POST')
          @if(!$canEdit && !$isadminProdi)
            <div class="alert alert-warning alert-dismissible" role="alert">
              <small><i class="bx bx-lock"></i> Tidak dapat diedit karena mahasiswa memilih dosen pembimbing lain.</small>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
          <div class="mb-3">
            @if($isDosen && !$isadminProdi)
              <label class="form-label text-danger"><strong>Judul:</strong></label>
              <textarea name="judul" class="form-control" @if(!$canEdit) readonly @endif>{{ $ta->judul_revisi ?? '' }}</textarea>
              <small class="text-muted">* Judul dapat diubah jika dosen pembimbing belum memberikan persetujuan atau penolakan status.</small>
            @else
              <label class="form-label"><strong>Judul yang diusulkan:</strong></label>
              <p>{{ $ta->judul }}</p>
              @if($ta->status_revisi == '2')
                <label class="form-label text-danger"><strong>Judul Revisi oleh Dosen:</strong></label>
                <p>{{ $ta->judul_revisi ?? '-' }}</p>
              @endif
            @endif
          </div>
            <div class="row mb-3">
              <div class="col-md-6">
              <label class="form-label"><strong>Jenis Penelitian:</strong></label>
              <p>{{ $ta->jenisPenelitian->nama ?? '-' }}</p>
              </div>
              @if($ta->bidangPeminatan)
                <div class="col-md-6" id="bidangPeminatanGroup">
                  <label class="form-label"><strong>Bidang Peminatan:</strong></label>
                  <p>{{ $ta->bidangPeminatan->nama ?? '-' }}</p>
                </div>
              @endif
            </div>
            <div class="row mb-3">
              <div class="col-md-6">
                <label class="form-label"><strong>Pembimbing dipilih Mahasiswa:</strong></label>
                <p>{{ $ta->pembimbing->name ?? '-' }}</p>
              </div>
              <div class="col-md-6">
                <label class="form-label"><strong>Draft :</strong></label>
                @if($ta->file)
                    <a href="{{ asset('storage/'.$ta->file) }}" download class="badge bg-primary">
                      <i class="bx bx-file"></i> Download Draft
                    </a>
                  @else
                    <span class="badge bg-secondary">Tidak ada Draft</span>
                  @endif
              </div>
            </div>
            <div class="mb-3">
            <label class="form-label"><strong>  Latar Belakang:</strong></label>
            <p>{{ $ta->latar_belakang ?? '-' }}</p>
            </div>
            <div class="mb-3">
            <label class="form-label"><strong>Permasalahan:</strong></label>
            <p>{{ $ta->permasalahan ?? '-' }}</p>
            </div>
            <div class="mb-3">
            <label class="form-label"><strong>Metode Penelitian:</strong></label>
            <p>{{ $ta->metode_penelitian ?? '-' }}</p>
            </div>
            
            <div class="mb-3">
            <label class="form-label text-danger"><strong>Status:</strong></label>
            @if($isDosen && !$isadminProdi)
              @php $st = $ta->status->first(); @endphp
              @php
                $selectedStatus = $ta->status_revisi ?? ($ta->status->first()->status ?? '');
              @endphp
              <select name="status[0][status]" class="form-select mb-1 status-select" data-taid="{{ $ta->id }}" data-selected="{{ $selectedStatus }}" {{ $canEdit ? '' : 'disabled' }} required>
                <option value="2" {{ $selectedStatus == '2' ? 'selected' : '' }}>Pengusulan DISETUJUI</option>
                <option value="3" {{ $selectedStatus == '3' ? 'selected' : '' }}>Pengusulan DITOLAK</option>
              </select>
            @else
              <div class="mb-1">
                <div class="d-inline-block w-100">
                  @if($ta->status_revisi == '2')
                    <span class="badge bg-primary text-wrap" style="white-space:normal;">
                      Pengusulan Disetujui dosen pembimbing, Tanggal : {{ $ta->created_at_revisi }}
                    </span>
                  @elseif($ta->status_revisi == '3')
                    <span class="badge bg-danger text-wrap" style="white-space:normal;">
                      Pengusulan Ditolak dosen pembimbing, Tanggal : {{ $ta->created_at_revisi }}
                    </span>
                  @else
                    -
                  @endif
                </div>
              </div>
            @endif
            </div>
            <div class="mb-3">
            <label id="catatan-label-{{ $ta->id }}" class="form-label text-danger">
              <strong>Catatan / Deskripsi Dosen :</strong>
            </label>
            @if($isDosen && $canEdit && !$isadminProdi)
              <div id="quill-editor-{{ $ta->id }}" class="quill-editor" style="height:120px;">{!! $ta->catatan_revisi !!}</div>
              <input type="hidden" name="status[0][catatan]" id="quill-input-{{ $ta->id }}" value="{{ $ta->catatan_revisi }}">
              <hr>
              <div class="mt-3">
                <button type="submit" class="btn btn-primary" {{ $canEdit ? '' : 'disabled' }}>Update Usulan</button>
              </div>
            @else
              <p>{!! $ta->catatan_revisi !!}</p>
            @endif
            </div>

          {{-- Bagian untuk admin prodi --}}
          @php
            $selectedPembimbingId = isset($ta->sk_proposal) && $ta->sk_proposal->pembimbing_id
            ? $ta->sk_proposal->pembimbing_id
            : $ta->pembimbing_id;
        @endphp
          <input type="hidden" name="tugas_akhir_id" value="{{ $ta->id }}">
          <input type="hidden" name="status_sk" value="">
          @if($isadminProdi && $statusAkhir && $statusAkhir->status == 2)
            @if(!$sk_proposal)
              <div class="mb-3">
                <label class="form-label text-primary"><strong>Keterangan</strong></label>
                <div id="quill-adminprodi-{{ $ta->id }}" class="quill-editor-adminprodi" style="height:120px;">{!! $ta->sk_proposal->keterangan ?? '' !!}</div>
                <input type="hidden" id="quill-adminprodi-input-{{ $ta->id }}" value="{{ $ta->sk_proposal->keterangan ?? '' }}">
              </div>
              <div class="mb-3">
                <label class="form-label text-primary">Sesuaikan Pembimbing (jika ada perubahan dari Prodi)</label>
                <select class="form-select" id="pembimbing_id_{{ $ta->id }}" required>
                  @foreach($dosenList as $dosen)
                    <option value="{{ $dosen->id }}" {{ $selectedPembimbingId == $dosen->id ? 'selected' : '' }}>
                      {{ $dosen->name }} - {{ $dosen->no_induk }}
                    </option>
                  @endforeach
                </select>
              </div>
              @else
              <div class="mb-3">
                <label class="form-label text-primary"><strong>SK Proposal yang sudah dibuat:</strong></label>
                <p>
                  <a href="{{ route('validasi-sk.cetak', ['id' => $ta->sk_proposal->id]) }}" class="badge bg-primary" target="_blank">
                    <i class="bx bx-file"></i> Lihat SK Proposal
                  </a>
                </p>
              </div>
            @endif
            <hr>
            <button type="button" class="btn btn-secondary" id="tolakBtn-{{ $ta->id }}">Tolak Usulan</button>
            <button type="button" class="btn btn-primary" id="setujuBtn-{{ $ta->id }}">Setujui & Buat SK</button>
          @endif
           </form>
      </div>
    @endforeach
  </div>
</div>

