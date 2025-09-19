<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SK Proposal</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .judul { font-size: 16px; font-weight: bold; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table td { padding: 4px; }
    </style>
</head>
<body>
    <div class="judul">SURAT KEPUTUSAN PROPOSAL</div>
    <table class="table">
        <tr>
            <td>Judul Revisi</td>
            <td>: {{ $sk->judul_revisi }}</td>
        </tr>
        <tr>
            <td>Pembimbing</td>
            <td>: {{ $pembimbing->name }}</td>
        </tr>
        <tr>
            <td>Tanggal SK</td>
            <td>: {{ \Carbon\Carbon::parse($sk->tanggal_sk)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Expired</td>
            <td>: {{ \Carbon\Carbon::parse($sk->tanggal_expired)->format('d-m-Y') }}</td>
        </tr>
        <tr>
            <td>Keterangan</td>
            <td>: {{ $sk->keterangan }}</td>
        </tr>
    </table>
    <div class="row" id="tugasAkhirList">
      @forelse($tugasAkhir as $ta)
        @include('tugas_akhir._card_tugas_akhir', ['ta' => $ta])
      @empty
        <div class="col-12" id="noTugasAkhirRow">
          <!-- ...alert belum ada tugas akhir... -->
        </div>
      @endforelse
    </div>
</body>
</html>