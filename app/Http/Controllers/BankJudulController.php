<?php

namespace App\Http\Controllers;

use App\Models\BankJudul;
use App\Models\User;
use App\Models\RekomendasiJudul;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class BankJudulController extends Controller
{
    public function index()
    {
        return view('bankJudul');
    }

    // Method baru untuk menangani request DataTable
    public function ajax()
    {
        $data = BankJudul::select([
            'bank_judul.id',
            'bank_judul.judul',
            'bank_judul.deskripsi',
            'bank_judul.status',
            'bank_judul.created_at',
            'users.name as created_by_name'
        ])
        ->leftJoin('users', 'users.id', '=', 'bank_judul.created_by');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                if (Auth::user()->hasAnyRole(['superadmin', 'admin prodi'])) {
                    return '<button class="btn btn-sm btn-icon btn-warning btnEdit" data-id="'.$row->id.'" title="Edit">
                                <i class="bx bx-edit"></i>
                            </button> 
                            <button class="btn btn-sm btn-icon btn-danger btnDelete" data-id="'.$row->id.'" title="Hapus">
                                <i class="bx bx-trash"></i>
                            </button>';
                }
                return '<button class="btn btn-sm btn-icon btn-primary btnDetail" data-id="'.$row->id.'" title="Lihat Detail">
                            <i class="bx bx-show"></i>
                        </button>';
            })
            ->editColumn('status', function($row){
                $statusText = $row->status == 1 ? 'selesai' : ($row->status == 2 ? 'aktif' : '-');
                $class = $row->status == 2 ? 'success' : 'secondary';
                return '<span class="badge bg-'.$class.'">'.$statusText.'</span>';
            })
            ->editColumn('created_at', function($row){
                return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }

    public function ajaxRekomendasi()
    {
        $data = RekomendasiJudul::with(['dosen', 'bidangPeminatan'])->select('rekomendasi_judul.*');

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('dosen_name', function($row){
                return $row->dosen ? $row->dosen->name : '-';
            })
            ->addColumn('bidang_peminatan', function($row){
                return $row->bidangPeminatan ? $row->bidangPeminatan->nama : '-';
            })
            ->editColumn('status', function($row){
                $statusText = $row->status == 1 ? 'aktif' : ($row->status == 2 ? 'nonaktif' : '-');
                $class = $row->status == 1 ? 'success' : 'secondary';
                return '<span class="badge bg-'.$class.'">'.$statusText.'</span>';
            })
            ->editColumn('created_at', function($row){
                return $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-';
            })
            ->addColumn('action', function($row){
                return '<button class="btn btn-sm btn-icon btn-primary btnSelect" data-id="'.$row->id.'" data-judul="'.$row->judul.'" title="Pilih Judul">
                            <i class="bx bx-check"></i>
                        </button>';
            })
            ->rawColumns(['action','status'])
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
}
