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
        @endphp

        <form id="form-update-{{ $ta->id }}" action="{{ route('tugas-akhir.revisi', $ta->id) }}" method="POST" class="form-update-ta">
          @csrf
          @method('POST')
          @if(!$canEdit)
            <div class="alert alert-warning alert-dismissible" role="alert">
              <small><i class="bx bx-lock"></i> Tidak dapat diedit karena mahasiswa memilih dosen pembimbing lain.</small>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif
            <div class="mb-3">
            <label class="form-label"><strong>Judul:</strong></label>
            <textarea name="judul" class="form-control" {{ $canEdit ? '' : 'readonly' }}>{{ $ta->judul }}</textarea>
            <small class="text-muted">* Judul dapat diubah jika dosen pembimbing belum memberikan persetujuan.</small>
            </div>
            <div class="mb-3">
            <label class="form-label"><strong>Jenis Penelitian:</strong></label>
              <select id="selectTypeOpt-{{ $ta->id }}" class="form-select" name="jenis_penelitian_id" data-value="{{ $ta->jenis_penelitian_id }}" {{ $canEdit ? '' : 'disabled' }} disabled>
              </select>
            </div>
          <div class="mb-3" id="bidangPeminatanGroup">
            <label class="form-label"><strong>Bidang Peminatan:</strong></label>
              <select id="selectBidangOpt-{{ $ta->id }}" class="form-select" name="bidang_peminatan_id" data-value="{{ $ta->bidang_peminatan_id }}" {{ $canEdit ? '' : 'disabled' }} disabled>
              </select>
          </div>
          <div class="mb-3">
            <label class="form-label"><strong>Pembimbing dipilih:</strong></label>
            <input type="text" name="pembimbing" class="form-control" value="{{ $ta->pembimbing->name ?? '-' }}" readonly>
          </div>
            <div class="mb-3">
            <label class="form-label"><strong>Latar Belakang:</strong></label>
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
              <label class="form-label"><strong>Draft :</strong></label>
              @if($ta->file)
                  <a href="{{ asset('storage/'.$ta->file) }}" download class="badge bg-primary">
                    <i class="bx bx-file"></i> Download Draft
                  </a>
                @else
                  <span class="badge bg-secondary">Tidak ada Draft</span>
                @endif
            </div>
            <div class="mb-3">
            <label class="form-label text-danger"><strong>Status:</strong></label>
            @php $st = $ta->status->first(); @endphp
            <select name="status[0][status]" class="form-select mb-1" {{ $canEdit ? '' : 'disabled' }}>
              <option value="1" {{ $st && $st->status == '1' ? 'selected' : '' }}>Belum Diperiksa</option>
              <option value="2" {{ $st && $st->status == '2' ? 'selected' : '' }}>Disetujui, Revisi</option>
              <option value="3" {{ $st && $st->status == '3' ? 'selected' : '' }}>Disetujui, Tanpa Revisi</option>
              <option value="4" {{ $st && $st->status == '4' ? 'selected' : '' }}>Tolak Pengusulan</option>
            </select>
            </div>
          <div class="mb-3">
            <label class="form-label text-danger"><strong>Catatan / Revisi Pembimbing:</strong></label>
            @foreach($ta->status as $i => $st)
              <div>
                <div id="quill-editor-{{ $ta->id }}-{{ $i }}" class="quill-editor" style="height:120px;">{!! $st->catatan !!}</div>
                <input type="hidden" name="status[{{ $i }}][catatan]" id="quill-input-{{ $ta->id }}-{{ $i }}" value="{{ $st->catatan }}">
              </div>
            @endforeach
          </div>        
          <button type="submit" class="btn btn-primary" {{ $canEdit ? '' : 'disabled' }}>Update Usulan</button>
        </form>

        
      </div>
    @endforeach
  </div>
</div>
