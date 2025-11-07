<?php

namespace App\Http\Controllers;

use App\Models\BankJudul;
use App\Models\User;
use App\Models\Prodi;
use App\Models\BidangPeminatan;
use App\Models\RekomendasiJudul;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class BankJudulController extends Controller
{
    public function index()
    {
        if (!Auth::user()->can('bank-judul-view')) {
            abort(403, 'Unauthorized action.');
        }
        $prodi = Prodi::all();
        $bidangPeminatan = BidangPeminatan::all();
        $dosen = User::role('dosen')->get();
        return view('bankJudul', compact('prodi', 'bidangPeminatan', 'dosen'));
    }

    public function ajax(Request $request)
    {
        if (!Auth::user()->can('bank-judul-view')) {
            abort(403, 'Unauthorized action.');
        }
        $data = BankJudul::with(['creator', 'prodi', 'bidangPeminatan', 'pembimbing', 'pembimbing2'])->select('bank_judul.*');
        return DataTables::of($data)
            ->filter(function ($query) use ($request) {
                $keywords = $request->get('search')['value'] ?? null;
                if ($keywords) {
                    $query->where(function ($q) use ($keywords) {
                        $q->where('bank_judul.judul', 'like', "%{$keywords}%")
                          ->orWhere('bank_judul.deskripsi', 'like', "%{$keywords}%")
                          ->orWhere('bank_judul.nama', 'like', "%{$keywords}%");
                    });
                }
            })
            ->addIndexColumn()
            ->addColumn('nama', fn($row) => $row->nama ? $row->nama : '-')
            ->addColumn('deskripsi', fn($row) => $row->deskripsi ? $row->deskripsi : '-')
            ->addColumn('prodi_nama', fn($row) => $row->prodi ? $row->prodi->nama : '-')
            ->addColumn('bidang_nama', fn($row) => $row->bidangPeminatan ? $row->bidangPeminatan->nama : '-')
            ->addColumn('pembimbing_name', fn($row) => $row->pembimbing ? $row->pembimbing->name : '-')
            ->addColumn('pembimbing2_name', fn($row) => $row->pembimbing2 ? $row->pembimbing2->name : '-')
            ->editColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-')
            ->addColumn('action', function($row){
                $btn = '';

                if (Auth::user()->can('bank-judul-edit')) {
                    $btn .= '<button class="btn btn-sm btn-icon btn-warning btnEdit" data-id="'.$row->id.'" title="Edit">
                                <i class="bx bx-edit"></i>
                            </button> ';
                }
                if (Auth::user()->can('bank-judul-delete')) {
                    $btn .= '<button class="btn btn-sm btn-icon btn-danger btnDelete" data-id="'.$row->id.'" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>';
                }
                return $btn ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function ajaxRekomendasi()
    {
        if (!Auth::user()->can('rekomendasi-judul-view')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $data = RekomendasiJudul::with(['dosen', 'bidangPeminatan'])->select('rekomendasi_judul.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dosen_name', fn($row) => $row->dosen ? $row->dosen->name : '-')
            ->addColumn('bidang_nama', fn($row) => $row->bidangPeminatan ? $row->bidangPeminatan->nama : '-')
            ->editColumn('created_at', fn($row) => $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-')
            ->addColumn('action', function($row){
                $btn = '';

                if (Auth::user()->can('rekomendasi-judul-select')) {
                    $btn .= '<button class="btn btn-sm btn-icon btn-primary btnSelect" data-id="'.$row->id.'" title="Pilih">
                                <i class="bx bx-check"></i>
                            </button> ';
                }

                if (Auth::user()->can('rekomendasi-judul-edit')) {
                    $btn .= '<button class="btn btn-sm btn-icon btn-warning btnEditRekom" data-id="'.$row->id.'" title="Edit">
                                <i class="bx bx-edit"></i>
                            </button> ';
                }

                if (Auth::user()->can('rekomendasi-judul-delete')) {
                    $btn .= '<button class="btn btn-sm btn-icon btn-danger btnDeleteRekom" data-id="'.$row->id.'" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>';
                }

                return $btn ?: '-';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function list()
    {
        // Method ini masih berguna untuk get by ID
        $id = request('id');
        if ($id) {
            return response()->json(BankJudul::find($id));
        }
        return response()->json(BankJudul::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'prodi_id' => 'nullable|integer',
            'bidang_peminatan_id' => 'nullable|integer',
        ]);
        $data['created_by'] = Auth::id();
        $judul = BankJudul::create($data);
        return response()->json(['success' => true, 'data' => $judul]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'prodi_id' => 'nullable|integer',
            'bidang_peminatan_id' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        $judul = BankJudul::findOrFail($id);
        $judul->update($data);
        return response()->json(['success' => true, 'data' => $judul]);
    }

    public function destroy($id)
    {
        $judul = BankJudul::findOrFail($id);
        $judul->delete();
        return response()->json(['success' => true]);
    }

    public function storeRekomendasi(Request $request)
    {
        if (!Auth::user()->can('rekomendasi-judul-create')) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }

        $data = $request->validate([
            'id_dosen' => 'required|exists:users,id',
            'bidang_peminatan_id' => 'required|exists:bidang_peminatan,id',
            'topik' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'format_penelitian' => 'nullable|string|max:255',
            'jenis_publikasi' => 'nullable|string|max:255',
        ]);
        
        $row = RekomendasiJudul::create($data);
        return response()->json(['success' => true, 'data' => $row]);
    }

    public function updateRekomendasi(Request $request, $id)
    {
        if (!Auth::user()->can('rekomendasi-judul-edit')) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki akses'], 403);
        }

        $data = $request->validate([
            'id_dosen' => 'required|exists:users,id',
            'bidang_peminatan_id' => 'required|exists:bidang_peminatan,id',
            'topik' => 'required|string|max:255',
            'judul' => 'required|string|max:255',
            'format_penelitian' => 'nullable|string|max:255',
            'jenis_publikasi' => 'nullable|string|max:255',
        ]);
        
        $row = RekomendasiJudul::findOrFail($id);
        $row->update($data);
        return response()->json(['success' => true, 'data' => $row]);
    }
}
