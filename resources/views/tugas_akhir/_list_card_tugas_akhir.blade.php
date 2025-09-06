<div class="row" id="tugasAkhirList">
  @forelse($tugasAkhir as $ta)
    @include('tugas_akhir._card_tugas_akhir', ['ta' => $ta])
  @empty
    <div class="col-12" id="noTugasAkhirRow">
      <!-- ...alert belum ada tugas akhir... -->
    </div>
  @endforelse
</div>