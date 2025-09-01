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
        $dosenList = User::role('dosen')->get();
        $ta = TugasAkhir::with('judul')->where('mahasiswa_id', $user->id)->latest()->first();
        return view('layout._card_tugas_akhir', ['ta' => $ta]);
    }

    public function store(Request $request)
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
            $user = auth()->user();
            $data = [
                'mahasiswa_id' => $user->id,
                'judul' => $request->judul,
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
            ->addColumn('jumlah_judul', function($ta) {
                return $ta->judul->count();
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
                    $statusText = '-';
                    $badgeClass = 'bg-secondary';
                    if ($status->status == 1) {
                        $statusText = 'Belum Diperiksa';
                        $badgeClass = 'bg-warning';
                    } elseif ($status->status == 2) {
                        $statusText = 'Disetujui';
                        $badgeClass = 'bg-success';
                    }
                    return '<span class="badge '.$badgeClass.'">'.$statusText.'</span> <small>'.$status->catatan.'</small>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->orderColumn('status', function($query, $order) {
                // Urutkan berdasarkan status terakhir
                $query->leftJoin('tugas_akhir_status as tas', function($join) {
                    $join->on('tas.tugas_akhir_id', '=', 'tugas_akhir.id');
                })
                ->orderBy('tas.status', $order)
                ->select('tugas_akhir.*');
            })
            ->addColumn('action', function($ta) {
                return '
                    <button class="btn btn-icon btn-primary editBtn" data-id="'.$ta->id.'">
                        <i class="bx bx-show-alt me-1"></i>
                    </button>
                ';
            })
            ->rawColumns(['judul', 'status', 'lampiran', 'action'])
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
        $ta->judul()->delete();
        $ta->status()->delete();
        $ta->delete();
        return response()->json(['success' => true, 'message' => 'Tugas Akhir berhasil dihapus!']);
    }
}
