<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkProposal;
use App\Models\TugasAkhir;
use PDF; // alias dari Niklasravnsborg\LaravelPdf\Facades\Pdf

class SKProposalController extends Controller
{
    public function cetak($id)
    {
        $sk = SkProposal::with(['tugasAkhir.pembimbing'])->findOrFail($id);

        return PDF::loadView('tugas_akhir.sk_proposal', [
            'sk' => $sk,
            'ta' => $sk->tugasAkhir,
            'pembimbing' => $sk->tugasAkhir->pembimbing,
        ])
        ->setPaper('a4')
        ->download('SK_Proposal_'.$sk->id.'.pdf');
    }
    public function create(Request $request)
    {
        $ta = TugasAkhir::with(['pembimbing'])->findOrFail($request->tugas_akhir_id);

        $sk = SkProposal::create([
            'tugas_akhir_id' => $ta->id,
            'judul_revisi'   => $ta->judul_revisi ?? $ta->judul,
            'pembimbing_id'  => $ta->pembimbing_id,
            'tanggal_sk'     => now(),
            'tanggal_expired'=> now()->addMonths(6), // contoh expired 6 bulan
            'keterangan'     => $request->keterangan ?? null,
            'status_aktif'   => true,
        ]);

        return response()->json(['success' => true, 'sk_id' => $sk->id]);
    }
}
