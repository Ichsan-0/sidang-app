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
use App\Models\ta_validasi_prodi;
use App\Models\SkProposal;
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
            $tugasAkhir = TugasAkhir::with(['jenisPenelitian', 'bidangPeminatan', 'pembimbing', 'status', 'sk_proposal'])
                ->where('mahasiswa_id', $user->id)
                ->get();
            foreach ($tugasAkhir as $ta) {
                $revisi = TugasAkhirRevisi::where('tugas_akhir_id', $ta->id)->latest()->first();
                $ta->has_revisi = $revisi ? true : false;
            }
            $sk_proposal = SkProposal::whereIn('tugas_akhir_id', $tugasAkhir->pluck('id'))
                ->where('status_aktif', 1)
                ->latest()
                ->first();

            return view('tugas_akhir/tugas_akhir_mahasiswa', [
                'prodi' => $prodi,
                'tugasAkhir' => $tugasAkhir,
                'dosenList' => User::role('dosen')->get(),
                'sk_proposal' => $sk_proposal,
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
        $revisi = $ta ? TugasAkhirRevisi::where('tugas_akhir_id', $ta->id)->latest()->first() : null;
        if ($ta) {
            $ta->has_revisi = $revisi ? true : false;
        }
        return view('tugas_akhir._card_tugas_akhir', ['ta' => $ta]);
    }

    public function all()
    {
        $user = auth()->user();
        $tugasAkhir = TugasAkhir::with(['jenisPenelitian', 'bidangPeminatan', 'pembimbing', 'status'])
            ->where('mahasiswa_id', $user->id)
            ->get();
        foreach ($tugasAkhir as $ta) {
            $revisi = TugasAkhirRevisi::where('tugas_akhir_id', $ta->id)->latest()->first();
            $ta->has_revisi = $revisi ? true : false;
        }
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

            $ta = TugasAkhir::create($data);
            
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
        $activeRole = session('active_role', $user->getRoleNames()->first());

        $query = TugasAkhir::select(
                'tugas_akhir.mahasiswa_id',
                DB::raw('COUNT(*) as jumlah_judul'),
                'users.name as nama_mahasiswa',
                'users.no_induk as nim_mahasiswa'
            )
            ->join('users', 'users.id', '=', 'tugas_akhir.mahasiswa_id')
            ->join(DB::raw('(SELECT tugas_akhir_id, MAX(id) as last_status_id FROM tugas_akhir_status GROUP BY tugas_akhir_id) as tas'), 'tugas_akhir.id', '=', 'tas.tugas_akhir_id')
            ->join('tugas_akhir_status as status_akhir', 'status_akhir.id', '=', 'tas.last_status_id')
            ->where('status_akhir.status', '>', 0)
            ->groupBy('tugas_akhir.mahasiswa_id', 'users.name', 'users.no_induk');

        // Filter sesuai role aktif
        if ($activeRole == 'admin prodi' || $activeRole == 'pimpinan') {
            $query->where('users.prodi_id', $user->prodi_id);
        } elseif ($activeRole == 'dosen') {
            $query->where('tugas_akhir.pembimbing_id', $user->id);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->editColumn('nama_mahasiswa', function($row) {
                return $row->nama_mahasiswa ?? '-';
            })
            ->addColumn('kode_prodi', function($row) {
                $kode = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)
                    ->join('users', 'users.id', '=', 'tugas_akhir.mahasiswa_id')
                    ->join('prodis', 'prodis.id', '=', 'users.prodi_id')
                    ->value('prodis.kode_prodi');
                return $kode ?? '-';
            })
            ->editColumn('status', function($row) {
                $user = auth()->user();

                // Untuk admin prodi dan superadmin
                if ($user->hasAnyRole(['admin prodi', 'superadmin'])) {
                    // Ambil semua tugas akhir untuk mahasiswa ini
                    $tugasAkhirIds = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)->pluck('id');

                    // Hanya hitung judul dengan status terakhir > 1
                    $jumlahJudul = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)
                        ->whereIn('id', function($query) {
                            $query->select('tugas_akhir_id')
                                ->from('tugas_akhir_status')
                                ->where('status', '!=', 0);
                        })
                        ->count();

                    $jumlahRevisi = TugasAkhirRevisi::whereIn('tugas_akhir_id', $tugasAkhirIds)->count();
                    $skProposal = SkProposal::whereIn('tugas_akhir_id', $tugasAkhirIds)
                        ->where('status_aktif', 1)
                        ->first();

                    if ($skProposal) {
                        return '<span class="badge bg-success">Disetujui & SK Aktif</span>';
                    } elseif ($jumlahJudul == 0) {
                        return '<span class="badge bg-secondary">Belum Mengajukan</span>';
                    } elseif ($jumlahJudul > 0 && $jumlahRevisi == 0) {
                        return '<span class="badge bg-warning text-white">Belum Validasi Dosen</span>';
                    } elseif ($jumlahJudul == $jumlahRevisi) {
                        return '<span class="badge bg-danger">Perlu Tinjauan Prodi</span>';
                    } elseif ($jumlahJudul > $jumlahRevisi) {
                        return '<span class="badge bg-warning text-white">'.($jumlahJudul - $jumlahRevisi).' Belum Divalidasi</span>';
                    } else {
                        return '<span class="badge bg-warning text-white">Belum Diperiksa</span>';
                    }
                }

                // Untuk dosen
                if ($user->hasRole('dosen')) {
                    $query = TugasAkhir::where('mahasiswa_id', $row->mahasiswa_id)
                        ->where('pembimbing_id', $user->id);
                    $tugasAkhirIds = $query->pluck('id');

                    $lastStatus = TugasAkhirStatus::whereIn('tugas_akhir_id', $tugasAkhirIds)
                        ->where('status', '>', 0)
                        ->latest()
                        ->first();

                    $jumlahJudul = $query->count();
                    $jumlahRevisi = TugasAkhirRevisi::whereIn('tugas_akhir_id', $tugasAkhirIds)->count();

                    if ($lastStatus && $lastStatus->status == 1) {
                        return '<span class="badge bg-warning text-white">Belum diperiksa</span>';
                    } elseif ($jumlahJudul == $jumlahRevisi) {
                        return '<span class="badge bg-primary">Selesai Validasi</span>';
                    } elseif ($jumlahJudul > $jumlahRevisi) {
                        return '<span class="badge bg-info text-white">'.($jumlahJudul - $jumlahRevisi).' Belum Diperiksa</span>';
                    } else {
                        return '<span class="badge bg-warning text-white">Belum Diperiksa</span>';
                    }
                }

                // Untuk role lain (default)
                return '<span class="badge bg-secondary">-</span>';
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
        $usulan = TugasAkhir::with([
            'jenisPenelitian',
            'bidangPeminatan',
            'pembimbing',
            'sk_proposal',
            'status' => function($q) {
                $q->orderByDesc('created_at');
            },
            'validasiProdi'
        ])
        ->where('mahasiswa_id', $mahasiswaId)
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
            $revisi = \DB::table('tugas_akhir_revisi')
                ->where('tugas_akhir_id', $ta->id)
                ->orderByDesc('created_at')
                ->first();
            $ta->has_revisi = $revisi ? true : false;
            $ta->catatan_revisi = $revisi ? $revisi->catatan : '';
            $ta->judul_revisi = $revisi ? $revisi->judul : $ta->judul;
            $ta->status_revisi = $revisi ? $revisi->status_revisi : null;
            $ta->created_at_revisi = $revisi && $revisi->created_at ? \Carbon\Carbon::parse($revisi->created_at)->format('d/m/Y') : null;
        }

        $JenisPenelitianList = JenisPenelitian::all();
        $BidangPeminatanList = BidangPeminatan::all();
        $sk_proposal = SkProposal::whereIn('tugas_akhir_id', $usulan->pluck('id'))->latest()->first();
        $dosenList = User::role('dosen')->get();
        $validasiProdi = ta_validasi_prodi::whereIn('tugas_akhir_id', $usulan->pluck('id'))->first();
        $editableIds = [];
        if ($user->hasRole('dosen')) {
            foreach ($usulan as $ta) {
            if ($ta->pembimbing_id == $user->id) {
                $editableIds[] = $ta->id;
            }
            }
        }

        return view('tugas_akhir._detail_tugas_akhir', compact(
            'usulan',
            'JenisPenelitianList',
            'BidangPeminatanList',
            'editableIds',
            'dosenList',
            'sk_proposal',
            'validasiProdi',
        ));
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
                $existingRevisi = DB::table('tugas_akhir_revisi')
                    ->where('tugas_akhir_id', $ta->id)
                    ->orderByDesc('created_at')
                    ->first();

                $dataRevisi = [
                    'judul' => $request->judul,
                    'catatan' => $request->input('status')[0]['catatan'] ?? '',
                    'status_revisi' => $request->input('status')[0]['status'] ?? null,
                    'updated_at' => now()
                ];

                if ($existingRevisi) {
                    // Update revisi yang sudah ada
                    DB::table('tugas_akhir_revisi')
                        ->where('id', $existingRevisi->id)
                        ->update($dataRevisi);
                } else {
                    // Insert revisi baru
                    $dataRevisi['tugas_akhir_id'] = $ta->id;
                    $dataRevisi['created_at'] = now();
                    DB::table('tugas_akhir_revisi')->insert($dataRevisi);
                }
            }
            // Tambahkan status baru
            $statusData = $request->input('status', []);
            foreach ($statusData as $st) {
                $catatan = $st['catatan'];
                if ($st['status'] == 2) {
                    $catatan = 'Disetujui';
                } elseif ($st['status'] == 3) {
                    $catatan = 'Ditolak';
                }
                TugasAkhirStatus::create([
                    'tugas_akhir_id' => $ta->id,
                    'status' => $st['status'],
                    'catatan' => $catatan,
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
    public function revisiDetail($id)
    {
        $revisi = \DB::table('tugas_akhir_revisi')
            ->where('tugas_akhir_id', $id)
            ->orderByDesc('created_at')
            ->first();
        
        $tugasAkhir = TugasAkhir::find($id);

        $sk_proposal = SkProposal::where('tugas_akhir_id', $id)
            ->where('status_aktif', 1)
            ->first();
        return response()->json([
            'tugasAkhir' => $tugasAkhir,
            'judul_revisi' => $revisi ? $revisi->judul : '',
            'catatan_revisi' => $revisi ? $revisi->catatan : '',
            'status_revisi' => $revisi ? $revisi->status_revisi : '',
            'created_at_revisi' => $revisi && $revisi->created_at ? \Carbon\Carbon::parse($revisi->created_at)->format('d/m/Y') : '',
            'sk_proposal' => $sk_proposal ? true : false,
            'created_at_proposal' => $sk_proposal && $sk_proposal->created_at ? \Carbon\Carbon::parse($sk_proposal->created_at)->format('d/m/Y') : '',
            'catatan_sk' => $sk_proposal ? $sk_proposal->keterangan : '',
            'status_sk' => $sk_proposal ? ($sk_proposal->status_aktif ? 'SK Aktif' : 'SK Tidak Aktif') : 'Belum Ada SK',
            'tanggal_sk' => $sk_proposal && $sk_proposal->tanggal_sk ? \Carbon\Carbon::parse($sk_proposal->tanggal_sk)->format('d/m/Y') : '',
            'tanggal_expired' => $sk_proposal && $sk_proposal->tanggal_expired ? \Carbon\Carbon::parse($sk_proposal->tanggal_expired)->format('d/m/Y') : '',
            'kode_sk' => $sk_proposal ? $sk_proposal->kode_sk : '',
        ]);
    }
}
