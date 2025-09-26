<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SkProposal; // sesuaikan dengan model SK kamu

class ValidatorController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'nomor_sk' => 'required|string|max:100',
        ]);

        $sk = SkProposal::with(['tugasAkhir.mahasiswa', 'tugasAkhir.pembimbing'])
            ->where('kode_sk', $request->nomor_sk)
            ->first();

        if (!$sk) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'Nomor SK tidak ditemukan dalam sistem.',
            ], 404);
        }

        // Ambil data dinamis dari relasi
        $mahasiswa = $sk->tugasAkhir->mahasiswa ?? null;
        $pembimbing = $sk->tugasAkhir->pembimbing ?? null;

        return response()->json([
            'status' => 'ok',
            'message' => 'Data SK ditemukan dan valid.',
            'data' => [
                'Nomor SK'      => $sk->kode_sk,
                'Nama Mahasiswa'=> $mahasiswa ? ($mahasiswa->name ?? $mahasiswa->nama) : '-',
                'NIM'           => $mahasiswa ? ($mahasiswa->no_induk ?? $mahasiswa->nim) : '-',
                'Judul Skripsi' => $sk->judul_akhir ?? '-',
                'Pembimbing'    => $pembimbing ? ($pembimbing->name ?? $pembimbing->nama) : '-',
                'Status'        => ($sk->status_aktif == 1) ? 'Aktif' : '-',
                'Tanggal Berlaku' => $sk->tanggal_sk ? \Carbon\Carbon::parse($sk->tanggal_sk)->translatedFormat('d F Y') : '-',
                'Tanggal Berakhir' => $sk->tanggal_expired ? \Carbon\Carbon::parse($sk->tanggal_expired)->translatedFormat('d F Y') : '-',

            ],
        ]);
    }
}
