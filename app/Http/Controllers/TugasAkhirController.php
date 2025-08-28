<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\TugasAkhir;
use App\Models\TugasAkhirJudul;
use App\Models\TugasAkhirStatus;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TugasAkhirController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $prodi = Prodi::find($user->prodi_id);

        if($user->hasRole('mahasiswa')) {
            $tugasAkhir = TugasAkhir::with(['judul', 'jenisPenelitian', 'bidangPeminatan'])
                ->where('mahasiswa_id', $user->id)
                ->get();
        } else {
            $tugasAkhir = collect(); // kosongkan, DataTables akan handle via AJAX
        }
        
        return view('tugas_akhir', [
            'prodi' => $prodi,
            'tugasAkhir' => $tugasAkhir,
            'dosenList' => User::role('dosen')->get(),
        ]);
    }
    public function last()
    {
        $user = auth()->user();
        $dosenList = User::role('dosen')->get();
        $ta = TugasAkhir::with('judul')->where('mahasiswa_id', $user->id)->latest()->first();
        return view('layout._card_tugas_akhir', ['ta' => $ta]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|array|min:1',
            'judul.*' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_penelitian_id' => 'required|exists:jenis_penelitians,id',
            'bidang_peminatan_id' => 'nullable|exists:bidang_peminatans,id',
            'pembimbing_id' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data = [
                'mahasiswa_id' => $user->id,
                'deskripsi' => $request->deskripsi,
                'jenis_penelitian_id' => $request->jenis_penelitian_id,
                'bidang_peminatan_id' => $request->bidang_peminatan_id,
                'pembimbing_id' => $request->pembimbing_id,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'draft_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tugas_akhir', $filename, 'public');
                $data['file'] = $path;
            }

            $ta = TugasAkhir::create($data);

            // Simpan semua judul
            foreach ($request->judul as $judul) {
                TugasAkhirJudul::create([
                    'tugas_akhir_id' => $ta->id,
                    'judul' => $judul
                ]);
            }

            // Simpan status awal (misal 1 = diajukan)
            TugasAkhirStatus::create([
                'tugas_akhir_id' => $ta->id,
                'status' => 1, // atau 'diajukan' jika pakai string
                'catatan' => "Diajukan ",
                'user_id' => $user->id
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Usulan Tugas Akhir berhasil dikirim!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function ajax(Request $request)
    {
        $user = auth()->user();

        if ($user->hasRole('mahasiswa')) {
            // Tidak perlu DataTables untuk mahasiswa
            return response()->json([]);
        }

        $query = TugasAkhir::with(['judul', 'jenisPenelitian', 'bidangPeminatan', 'pembimbing', 'status', 'mahasiswa'])
            ->whereHas('mahasiswa', function($q) use ($user) {
                $q->where('prodi_id', $user->prodi_id);
            });

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('nama_mahasiswa', function($ta) {
                return $ta->mahasiswa->name ?? '-';
            })
            ->filterColumn('nama_mahasiswa', function($query, $keyword) {
                $query->whereHas('mahasiswa', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('judul', function($ta) {
                return '<ul>' . collect($ta->judul)->pluck('judul')->map(fn($j) => "<li>$j</li>")->implode('') . '</ul>';
            })
            ->filterColumn('judul', function($query, $keyword) {
                $query->whereHas('judul', function($q) use ($keyword) {
                    $q->where('judul', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('jenis_tugas_akhir', function($ta) {
                return $ta->jenisPenelitian->nama ?? '-';
            })
            ->filterColumn('jenis_tugas_akhir', function($query, $keyword) {
                $query->whereHas('jenisPenelitian', function($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('bidang_peminatan', function($ta) {
                return $ta->bidangPeminatan->nama ?? '-';
            })
            ->filterColumn('bidang_peminatan', function($query, $keyword) {
                $query->whereHas('bidangPeminatan', function($q) use ($keyword) {
                    $q->where('nama', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('pembimbing', function($ta) {
                return $ta->pembimbing->name ?? '-';
            })
            ->filterColumn('pembimbing', function($query, $keyword) {
                $query->whereHas('pembimbing', function($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('status', function($ta) {
                $status = $ta->status()->latest()->first();
                if ($status) {
                    return '<span class="badge bg-info">'.$status->status.'</span> <small>'.$status->catatan.'</small>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('lampiran', function($ta) {
                if ($ta->file) {
                    return '<a href="'.asset('storage/'.$ta->file).'" target="_blank">Lihat</a>';
                }
                return '-';
            })
            ->rawColumns(['judul', 'status', 'lampiran'])
            ->toJson();
    }
}
