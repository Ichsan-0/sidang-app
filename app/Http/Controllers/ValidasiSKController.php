<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkProposal;
use App\Models\TugasAkhir;
use App\Models\ta_validasi_prodi;
use App\Models\User;
use PDF; // alias dari Niklasravnsborg\LaravelPdf\Facades\Pdf
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class ValidasiSKController extends Controller
{
    public function cetak($id)
    {
        \Carbon\Carbon::setLocale('id');
         $sk = SkProposal::with([
            'tugasAkhir.pembimbing',
            'tugasAkhir.bidangPeminatan'
        ])->findOrFail($id);
        $penandatangan = $sk->ttd ? User::find($sk->ttd) : null;

        // Generate QR Code dengan kode_sk saja
        $qrCode = QrCode::create($sk->kode_sk)->setSize(90)->setMargin(5);
        $writer = new PngWriter();
        $qrPng = $writer->write($qrCode)->getString();
        $qrBase64 = 'data:image/png;base64,' . base64_encode($qrPng);

        return PDF::loadView('tugas_akhir.sk_proposal', [
            'sk' => $sk,
            'ta' => $sk->tugasAkhir,
            'pembimbing' => $sk->pembimbing,
            'bidangPeminatan' => $sk->tugasAkhir->bidangPeminatan,
            'penandatangan' => $penandatangan,
            'qrBase64' => $qrBase64
        ], [], [
            'format' => 'legal',
            'orientation' => 'P',
            'margin_top' => 5,
            'margin_right' => 10,
            'margin_bottom' => 10,
            'margin_left' => 10
        ])
        ->download('SK_Proposal_' . ($sk->tugasAkhir->mahasiswa->name ?? 'Mahasiswa') . '.pdf');

        /*return view('tugas_akhir.sk_proposal', [
            'sk' => $sk,
            'ta' => $sk->tugasAkhir,
            'pembimbing' => $sk->pembimbing,
            'bidangPeminatan' => $sk->tugasAkhir->bidangPeminatan,
            'penandatangan' => $penandatangan,
            'qrBase64' => $qrBase64
        ]);*/
    }
    public function create(Request $request)
    {
        $ta = TugasAkhir::with(['pembimbing', 'mahasiswa.prodi'])->findOrFail($request->tugas_akhir_id);

        $validasi = ta_validasi_prodi::create([
            'tugas_akhir_id' => $ta->id,
            'user_id'        => auth()->id(),
            'pembimbing_id'  => $request->pembimbing_id ?? $ta->pembimbing_id,
            'status'         => $request->status_sk,
            'catatan'        => $request->keterangan_sk ?? null, 
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);
        // Jika status 'Y', buat SK Proposal
        if ($request->status_sk == 'Y') {
            // Ambil kode prodi dari relasi mahasiswa -> prodi
            $kodeProdi = ($ta->mahasiswa && $ta->mahasiswa->prodi)
            ? $ta->mahasiswa->prodi->kode_prodi
            : 'XX';

            // Ambil bulan dan tahun dari tanggal_sk
            $tanggalSk = now();
            $bulan = $tanggalSk->format('m');
            $tahun = $tanggalSk->format('Y');

            // Ambil judul revisi dari tabel tugas_akhir_revisi berdasarkan tugas_akhir_id
            $judulRevisi = \DB::table('tugas_akhir_revisi')
            ->where('tugas_akhir_id', $ta->id)
            ->orderByDesc('id')
            ->value('judul');
            $ttd = User::whereHas('roles', function($q) {
                    $q->where('name', 'pimpinan');
                })
                ->where('prodi_id', $ta->mahasiswa->prodi_id)
                ->first();
            // Buat SK Proposal terlebih dahulu agar dapatkan id
            $sk = SkProposal::create([
                'tugas_akhir_id' => $ta->id,
                // Ambil judul revisi saja dari input form (jika ada), kosongkan jika tidak ada
                'judul_akhir'   => $judulRevisi,
                'pembimbing_id'  => $request->pembimbing_id ?? $ta->pembimbing_id,
                'tanggal_sk'     => $tanggalSk,
                'tanggal_expired'=> $tanggalSk->copy()->addMonths(2),
                'ttd'            => $ttd ? $ttd->id : null,
                'keterangan'     => $request->keterangan_sk ?? null,
                'status_aktif'   => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Generate kode_sk
            $sk->kode_sk = 'B-' . $sk->id . '/Un.08/' . $kodeProdi . '/' . $bulan . '/' . $tahun;
            $sk->save();
            return response()->json(['success' => true, 'sk_id' => $sk->id]);
        } else {
            // Jika status 'N', proses penolakan (misal update status TA)
            $ta->update(['status_revisi' => 3]);
            // ...tambahkan proses lain jika perlu...
            return response()->json(['success' => true, 'message' => 'Usulan ditolak']);
        }
    }
}
