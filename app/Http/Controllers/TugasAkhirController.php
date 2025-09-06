<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\TugasAkhir;
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
            $tugasAkhir = TugasAkhir::with(['jenisPenelitian', 'bidangPeminatan', 'pembimbing', 'status'])
                ->where('mahasiswa_id', $user->id)
                ->get();

            return view('tugas_akhir/tugas_akhir_mahasiswa', [
                'prodi' => $prodi,
                'tugasAkhir' => $tugasAkhir,
                'dosenList' => User::role('dosen')->get(),
            ]);
        } else {
            return view('tugas_akhir/tugas_akhir_admin', [
                'prodi' => $prodi,
                'dosenList' => User::role('dosen')->get(),
            ]);
        }
    }
    public function last()
    {
        $user = auth()->user();
        $ta = TugasAkhir::where('mahasiswa_id', $user->id)->latest()->first();
        return view('tugas_akhir._card_tugas_akhir', ['ta' => $ta]);
    }

    public function all()
    {
        $user = auth()->user();
        $tugasAkhir = TugasAkhir::with(['jenisPenelitian', 'bidangPeminatan', 'pembimbing', 'status'])
            ->where('mahasiswa_id', $user->id)
            ->get();

        return view('tugas_akhir._list_card_tugas_akhir', ['tugasAkhir' => $tugasAkhir]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_penelitian_id' => 'required|exists:jenis_penelitians,id',
            'bidang_peminatan_id' => 'nullable|exists:bidang_peminatan,id',
            'pembimbing_id' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user();
            $data = [
                'mahasiswa_id' => $user->id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'jenis_penelitian_id' => $request->jenis_penelitian_id,
                'bidang_peminatan_id' => $request->bidang_peminatan_id,
                'latar_belakang' => $request->latar_belakang,
                'permasalahan' => $request->permasalahan,
                'metode_penelitian' => $request->metode_penelitian,
                'pembimbing_id' => $request->pembimbing_id,
            ];

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'draft_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tugas_akhir', $filename, 'public');
                $data['file'] = $path;
            }

            $ta = TugasAkhir::create($data);

            TugasAkhirStatus::create([
                'tugas_akhir_id' => $ta->id,
                'status' => 1,
                'catatan' => "Diajukan",
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

        $query = TugasAkhir::select(
                'tugas_akhir.mahasiswa_id',
                DB::raw('COUNT(*) as jumlah_judul'),
                'users.name as nama_mahasiswa'
            )
            ->join('users', 'users.id', '=', 'tugas_akhir.mahasiswa_id')
            ->groupBy('tugas_akhir.mahasiswa_id', 'users.name');

        // Filter sesuai role
        if ($user->hasRole('admin prodi')) {
            $query->where('users.prodi_id', $user->prodi_id);
        } elseif ($user->hasRole('dosen')) {
            $query->where('tugas_akhir.pembimbing_id', $user->id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_mahasiswa', function($row) {
                return $row->nama_mahasiswa;
            })
            ->editColumn('jumlah_judul', function($row) {
                return $row->jumlah_judul;
            })
            ->addColumn('kode_prodi', function($row) {
                $kode = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)
                    ->join('users', 'users.id', '=', 'tugas_akhir.mahasiswa_id')
                    ->join('prodis', 'prodis.id', '=', 'users.prodi_id')
                    ->value('prodis.kode_prodi');
                return $kode ?? '-';
            })
            ->addColumn('status', function($row) {
                $tugasAkhirIds = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)->pluck('id');
                $statusList = TugasAkhirStatus::whereIn('tugas_akhir_id', $tugasAkhirIds)->pluck('status');
                if ($statusList->count() > 0 && $statusList->every(fn($s) => $s == 1)) {
                    return '<span class="badge bg-warning text-dark">Belum diperiksa</span>';
                }
                $lastStatus = TugasAkhirStatus::whereIn('tugas_akhir_id', $tugasAkhirIds)->latest()->first();
                if ($lastStatus && $lastStatus->status == 1) {
                    return '<span class="badge bg-warning text-dark">Belum diperiksa</span>';
                } elseif ($lastStatus) {
                    return '<span class="badge bg-success">Sudah diperiksa</span>';
                } else {
                    return '<span class="badge bg-secondary">-</span>';
                }
            })
            ->addColumn('action', function($row) {
                return '
                    <button class="btn btn-sm btn-icon btn-primary detailBtn" data-id="'.$row->mahasiswa_id.'">
                        <i class="bx bx-show-alt me-1"></i>
                    </button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->toJson();
    }

    public function edit($id)
    {
        $ta = TugasAkhir::with(['jenisPenelitian', 'bidangPeminatan', 'pembimbing'])->findOrFail($id);
        return response()->json([
            'id' => $ta->id,
            'judul' => $ta->judul,
            'jenis_penelitian_id' => $ta->jenis_penelitian_id,
            'bidang_peminatan_id' => $ta->bidang_peminatan_id,
            'pembimbing_id' => $ta->pembimbing_id,
            'deskripsi' => $ta->deskripsi,
            'latar_belakang' => $ta->latar_belakang,
            'permasalahan' => $ta->permasalahan,
            'metode_penelitian' => $ta->metode_penelitian,
            'file' => $ta->file,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'jenis_penelitian_id' => 'required|exists:jenis_penelitians,id',
            'bidang_peminatan_id' => 'nullable|exists:bidang_peminatans,id',
            'pembimbing_id' => 'nullable|string|max:255',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $ta = TugasAkhir::findOrFail($id);
            $ta->update([
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'jenis_penelitian_id' => $request->jenis_penelitian_id,
                'bidang_peminatan_id' => $request->bidang_peminatan_id,
                'pembimbing_id' => $request->pembimbing_id,
                'latar_belakang' => $request->latar_belakang,
                'permasalahan' => $request->permasalahan,
                'metode_penelitian' => $request->metode_penelitian,
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = 'draft_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('tugas_akhir', $filename, 'public');
                $ta->file = $path;
                $ta->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Tugas Akhir berhasil diupdate!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $ta = TugasAkhir::findOrFail($id);
        //$ta->judul()->delete();
        $ta->status()->delete();
        $ta->delete();
        return response()->json(['success' => true, 'message' => 'Tugas Akhir berhasil dihapus!']);
    }

    
}
