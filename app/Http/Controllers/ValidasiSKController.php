<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkProposal;
use App\Models\TugasAkhir;
use App\Models\ta_validasi_prodi;
use PDF; // alias dari Niklasravnsborg\LaravelPdf\Facades\Pdf

class ValidasiSKController extends Controller
{
    public function cetak($id)
    {
        $sk = SkProposal::with(['tugasAkhir.pembimbing'])->findOrFail($id);

        return PDF::loadView('tugas_akhir.sk_proposal', [
            'sk' => $sk,
            'ta' => $sk->tugasAkhir,
            'pembimbing' => $sk->tugasAkhir->pembimbing,
        ], [], [  // <== param 3 untuk data tambahan, param 4 untuk config mPDF
            'format' => 'A4',
            'orientation' => 'P',   // P = portrait, L = landscape
            'margin_top' => 15,
            'margin_right' => 15,
            'margin_bottom' => 15,
            'margin_left' => 15
        ])
        ->download('SK_Proposal_'.$sk->id.'.pdf');
    }
    public function create(Request $request)
    {
        $ta = TugasAkhir::with(['pembimbing', 'mahasiswa.prodi'])->findOrFail($request->tugas_akhir_id);

        $validasi = ta_validasi_prodi::create([
            'tugas_akhir_id' => $ta->id,
            'user_id'        => auth()->id(),
            'pembimbing_id'  => $request->pembimbing_id ?? $ta->pembimbing_id,
            'status'         => $request->status_sk,
            'catatan'        => $request->catatan_sk ?? null,
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

            // Buat SK Proposal terlebih dahulu agar dapatkan id
            $sk = SkProposal::create([
                'tugas_akhir_id' => $ta->id,
                'judul_revisi'   => $ta->judul_revisi ?? $ta->judul,
                'pembimbing_id'  => $request->pembimbing_id ?? $ta->pembimbing_id,
                'tanggal_sk'     => $tanggalSk,
                'tanggal_expired'=> $tanggalSk->copy()->addMonths(3),
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
