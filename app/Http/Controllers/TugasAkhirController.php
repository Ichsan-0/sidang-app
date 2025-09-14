<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use App\Models\TugasAkhir;
use App\Models\TugasAkhirStatus;
use App\Models\TugasAkhirRevisi;
use App\Models\User;
use App\Models\JenisPenelitian;
use App\Models\BidangPeminatan;
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
                $user = auth()->user();
                $namaMahasiswa = preg_replace('/[^A-Za-z0-9]/', '_', $user->name);
                $nimMahasiswa = $user->no_induk ?? 'nim';
                $noUnik = substr(str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT), 0, 3);
                $filename = 'draft_usulan_' . $namaMahasiswa . '_' . $nimMahasiswa . '_' . $noUnik . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/usulan'), $filename);
                $ta->file = 'usulan/' . $filename;
                $ta->save();
            }

            $ta = TugasAkhir::create($data);

            $statusValue = $request->input('status', 0); // default 0
            TugasAkhirStatus::create([
                'tugas_akhir_id' => $ta->id,
                'status' => $statusValue,
                'catatan' => $statusValue == 1 ? "Diajukan" : "Disimpan",
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
                'users.name as nama_mahasiswa',
                'users.no_induk as nim_mahasiswa'
            )
            ->join('users', 'users.id', '=', 'tugas_akhir.mahasiswa_id')
            // Join status terakhir
            ->join(DB::raw('(SELECT tugas_akhir_id, MAX(id) as last_status_id FROM tugas_akhir_status GROUP BY tugas_akhir_id) as tas'), 'tugas_akhir.id', '=', 'tas.tugas_akhir_id')
            ->join('tugas_akhir_status as status_akhir', 'status_akhir.id', '=', 'tas.last_status_id')
            ->where('status_akhir.status', '>', 0) // hanya status akhir > 0
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
                    return '<span class="badge bg-warning text-white">Belum diperiksa</span>';
                }
                $lastStatus = TugasAkhirStatus::whereIn('tugas_akhir_id', $tugasAkhirIds)->latest()->first();
                if ($lastStatus && $lastStatus->status == 1) {
                    return '<span class="badge bg-warning text-dark">Belum diperiksa</span>';
                } elseif ($lastStatus) {
                    return '<span class="badge bg-secondary">Belum selesai Pengajuan</span>';
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
                $user = auth()->user();
                $namaMahasiswa = preg_replace('/[^A-Za-z0-9]/', '_', $user->name);
                $nimMahasiswa = $user->no_induk ?? 'nim';
                $noUnik = substr(str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT), 0, 3);
                $filename = 'draft_usulan_' . $namaMahasiswa . '_' . $nimMahasiswa . '_' . $noUnik . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/usulan'), $filename);
                $ta->file = 'usulan/' . $filename;
                $ta->save();
            }

            // Update status jika ada
            if ($request->has('status')) {
                TugasAkhirStatus::create([
                    'tugas_akhir_id' => $ta->id,
                    'status' => $request->status,
                    'catatan' => $request->status == 1 ? "Diajukan" : "Disimpan",
                    'user_id' => auth()->id()
                ]);
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

    public function detail($mahasiswaId)
    {
        $user = auth()->user();
        // Ambil semua usulan tugas akhir mahasiswa
        $usulan = TugasAkhir::with([
            'jenisPenelitian',
            'bidangPeminatan',
            'pembimbing',
            // Ambil status terakhir
            'status' => function($q) {
                $q->orderByDesc('created_at');
            }
        ])
        ->where('mahasiswa_id', $mahasiswaId)
        // Filter hanya usulan dengan status akhir > 0
        ->whereIn('id', function($sub) {
            $sub->select('tugas_akhir_id')
                ->from('tugas_akhir_status')
                ->whereRaw('tugas_akhir_status.id = (
                    select max(id) from tugas_akhir_status as tas2
                    where tas2.tugas_akhir_id = tugas_akhir_status.tugas_akhir_id
                )')
                ->where('status', '>', 0);
        })
        ->get();

        // Ambil revisi terakhir untuk setiap usulan
        foreach ($usulan as $ta) {
            $ta->revisi = \DB::table('tugas_akhir_revisi')
                ->where('tugas_akhir_id', $ta->id)
                ->orderByDesc('created_at')
                ->first();
        }

        $JenisPenelitianList = JenisPenelitian::all();
        $BidangPeminatanList = BidangPeminatan::all();

        $editableIds = [];
        if ($user->hasAnyRole(['admin prodi', 'superadmin', 'pimpinan'])) {
            $editableIds = $usulan->pluck('id')->toArray();
        } elseif ($user->hasRole('dosen')) {
            foreach ($usulan as $ta) {
                if ($ta->pembimbing_id == $user->id) {
                    $editableIds[] = $ta->id;
                }
            }
        }

        return view('tugas_akhir._detail_tugas_akhir', compact('usulan', 'JenisPenelitianList', 'BidangPeminatanList', 'editableIds'));
    }

    public function revisi(Request $request, $id)
    {
        $user = auth()->user();
        $ta = TugasAkhir::findOrFail($id);

        // Validasi hanya dosen pembimbing yang boleh update
        if ($user->hasRole('dosen') && $ta->pembimbing_id != $user->id) {
            return response()->json(['success' => false, 'message' => 'Anda tidak berhak mengedit usulan ini.']);
        }

        DB::beginTransaction();
        try {
            // Simpan revisi jika ada
            if ($request->filled('judul')) {
                DB::table('tugas_akhir_revisi')->insert([
                    'tugas_akhir_id' => $ta->id,
                    'judul' => $request->judul,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            // Tambahkan status baru
            $statusData = $request->input('status', []);
            foreach ($statusData as $st) {
                TugasAkhirStatus::create([
                    'tugas_akhir_id' => $ta->id,
                    'status' => $st['status'],
                    'catatan' => $st['catatan'],
                    'user_id' => $user->id
                ]);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Revisi dan status berhasil disimpan!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
