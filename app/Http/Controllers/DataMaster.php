<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TahunAjaran; 
use App\Models\Fakultas;
use App\Models\JenisPenelitian;
use App\Models\BidangPeminatan;

class DataMaster extends Controller
{
    
    public function fakultas()
    {
        return view('master.fakultas');
    }

    public function ajaxFakultas(Request $request)
    {
        if ($request->ajax()) {
            $data = Fakultas::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item editBtn" data-id="'.$row->id.'">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button class="dropdown-item deleteBtn" data-id="'.$row->id.'">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    // Store Fakultas
    public function storeFakultas(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'ket'  => 'nullable|string',
        ]);

        $fakultas = Fakultas::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Fakultas berhasil ditambahkan', 'data' => $fakultas]);
    }

    // Edit Fakultas (get data)
    public function editFakultas($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        return response()->json($fakultas);
    }

    // Update Fakultas
    public function updateFakultas(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'ket'  => 'nullable|string',
        ]);

        $fakultas = Fakultas::findOrFail($id);
        $fakultas->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Fakultas berhasil diupdate']);
    }

    // Delete Fakultas
    public function deleteFakultas($id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $fakultas->delete();
        return response()->json(['success' => true, 'message' => 'Fakultas berhasil dihapus']);
    }
    public function prodi() 
    {
        $fakultas = Fakultas::all(); // Ambil data fakultas untuk dropdown
        return view('master.prodi', compact('fakultas'));
    }
    // AJAX DataTable
    public function ajaxProdi(Request $request)
    {
        if ($request->ajax()) {
            $data = Prodi::with('fakultas')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('nama_fakultas', function($row){
                    return $row->fakultas ? $row->fakultas->nama : '-';
                })
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item editBtn" data-id="'.$row->id.'">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button class="dropdown-item deleteBtn" data-id="'.$row->id.'">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    // Store Prodi
    public function storeProdi(Request $request)
    {
        $request->validate([
            'nama_prodi'   => 'required|string|max:255',
            'kode_prodi'   => 'required|string|max:50',
            'id_fakultas'  => 'required|exists:fakultas,id',
            'ket'          => 'nullable|string',
        ]);

        $prodi = Prodi::create([
            'nama_prodi' => $request->nama_prodi,
            'kode_prodi' => $request->kode_prodi,
            'id_fakultas' => $request->id_fakultas,
            'ket' => $request->ket ?? null, 
        ]);

        return response()->json(['success' => true, 'message' => 'Prodi berhasil ditambahkan', 'data' => $prodi]);
    }

    // Edit Prodi (get data)
    public function editProdi($id)
    {
        $prodi = Prodi::findOrFail($id);
        return response()->json($prodi);
    }

    // Update Prodi
    public function updateProdi(Request $request, $id)
    {
        $request->validate([
            'nama_prodi'   => 'required|string|max:255',
            'kode_prodi'   => 'required|string|max:50',
            'id_fakultas'  => 'required|exists:fakultas,id',
            'ket'          => 'nullable|string',
            'draft'        => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $prodi = \App\Models\Prodi::findOrFail($id);

        $data = [
            'nama_prodi' => $request->nama_prodi,
            'kode_prodi' => $request->kode_prodi,
            'id_fakultas' => $request->id_fakultas,
            'ket' => $request->ket ?? null,
        ];

        if ($request->hasFile('draft')) {
            $file = $request->file('draft');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('template_prodi', $filename, 'public');
            $data['draft'] = $path;
            // Optional: hapus file lama jika ada
            // if ($prodi->draft) Storage::disk('public')->delete($prodi->draft);
        }

        $prodi->update($data);

        return response()->json(['success' => true, 'message' => 'Prodi berhasil diupdate']);
    }

    // Delete Prodi
    public function deleteProdi($id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();
        return response()->json(['success' => true, 'message' => 'Prodi berhasil dihapus']);
    }

    public function getProdi()
    {
        $prodi = Prodi::with('fakultas')->get();

        // Mapping ke format yang sesuai
        $options = $prodi->map(function ($item) {
            return [
                'value' => $item->id,
                'text' => $item->nama_prodi,
                'optgroup' => $item->fakultas->nama ?? 'Lainnya', // Ganti nama_fakultas dengan nama
            ];
        });

        // Perbaiki mapping optgroups
        $optgroups = $prodi->whereNotNull('fakultas')->pluck('fakultas.nama')->unique()->map(function ($name) {
            return [
                'value' => $name,
                'label' => $name,
            ];
        })->values();

        return response()->json([
            'options' => $options,
            'optgroups' => $optgroups,
        ]);
    }

    public function getJenisPenelitian()
    {
        return JenisPenelitian::select('id', 'nama', 'ket')->get();
    }

    public function getBidangPeminatan()
    {
        return BidangPeminatan::select('id', 'nama')->get();
    }

    public function tahun()
    {
        return view('master.tahun');
    }
    public function ajaxTahun(Request $request)
        {
            if ($request->ajax()) {
            $data = TahunAjaran::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item editBtn" data-id="'.$row->id.'">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button class="dropdown-item deleteBtn" data-id="'.$row->id.'">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    // Store Tahun Ajaran
    public function storeTahun(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
        ]);

        $tahun = TahunAjaran::create([
            'nama' => $request->nama,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'is_aktif' => $request->is_aktif ?? 'n',
        ]);

        return response()->json(['success' => true, 'message' => 'Tahun ajaran berhasil ditambahkan', 'data' => $tahun]);
    }

    // Edit Tahun Ajaran (get data)
    public function editTahun($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        return response()->json($tahun);
    }

    // Update Tahun Ajaran
    public function updateTahun(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date',
        ]);

        $tahun = TahunAjaran::findOrFail($id);
        $tahun->update([
            'nama' => $request->nama,
            'periode_awal' => $request->periode_awal,
            'periode_akhir' => $request->periode_akhir,
            'is_aktif' => $request->is_aktif ?? 'n', 
        ]);

        return response()->json(['success' => true, 'message' => 'Tahun ajaran berhasil diupdate']);
    }

    // Delete Tahun Ajaran
    public function deleteTahun($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        $tahun->delete();
        return response()->json(['success' => true, 'message' => 'Tahun ajaran berhasil dihapus']);
    }


    // Tampilkan halaman jenis penelitian
    public function jenisPenelitian()
    {
        return view('master.jenis_penelitian');
    }

    // DataTable AJAX Jenis Penelitian
    public function ajaxJenisPenelitian(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisPenelitian::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item editBtn" data-id="'.$row->id.'">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button class="dropdown-item deleteBtn" data-id="'.$row->id.'">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    // Store Jenis Penelitian
    public function storeJenisPenelitian(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'ket'  => 'nullable|string',
        ]);

        $jenis = JenisPenelitian::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Jenis Penelitian berhasil ditambahkan', 'data' => $jenis]);
    }

    // Edit Jenis Penelitian (get data)
    public function editJenisPenelitian($id)
    {
        $jenis = JenisPenelitian::findOrFail($id);
        return response()->json($jenis);
    }

    // Update Jenis Penelitian
    public function updateJenisPenelitian(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'ket'  => 'nullable|string',
        ]);

        $jenis = JenisPenelitian::findOrFail($id);
        $jenis->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Jenis Penelitian berhasil diupdate']);
    }

    // Delete Jenis Penelitian
    public function deleteJenisPenelitian($id)
    {
        $jenis = JenisPenelitian::findOrFail($id);
        $jenis->delete();
        return response()->json(['success' => true, 'message' => 'Jenis Penelitian berhasil dihapus']);
    }

    public function ajaxBidangPeminatan(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\BidangPeminatan::query();

            // Filter by jenis_penelitian_id jika ada
            if ($request->has('jenis_penelitian_id') && $request->jenis_penelitian_id) {
                $query->where('jenis_penelitian_id', $request->jenis_penelitian_id);
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item editBidangBtn" data-id="'.$row->id.'">
                                <i class="bx bx-edit-alt me-1"></i> Edit
                            </button>
                            <button class="dropdown-item deleteBidangBtn" data-id="'.$row->id.'">
                                <i class="bx bx-trash me-1"></i> Delete
                            </button>
                        </div>
                    </div>';
                })
                ->rawColumns(['action'])
                ->toJson();
        }
    }

    // Store Bidang Peminatan
    public function storeBidangPeminatan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'prodi' => 'required|exists:prodis,id', // pastikan ini ada
            'ket'  => 'nullable|string',
        ]);

        $bidang = BidangPeminatan::create([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'id_prodi' => $request->prodi,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Bidang Peminatan berhasil ditambahkan', 'data' => $bidang]);
    }

    // Edit Bidang Peminatan (get data)
    public function editBidangPeminatan($id)
    {
        $bidang = \App\Models\BidangPeminatan::findOrFail($id);
        return response()->json($bidang);
    }

    // Update Bidang Peminatan
    public function updateBidangPeminatan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'required|string|max:50',
            'jenis_penelitian_id' => 'required|exists:jenis_penelitian,id',
            'ket'  => 'nullable|string',
        ]);

        $bidang = \App\Models\BidangPeminatan::findOrFail($id);
        $bidang->update([
            'nama' => $request->nama,
            'kode' => $request->kode,
            'jenis_penelitian_id' => $request->jenis_penelitian_id,
            'ket'  => $request->ket,
        ]);

        return response()->json(['success' => true, 'message' => 'Bidang Peminatan berhasil diupdate']);
    }

    // Delete Bidang Peminatan
    public function deleteBidangPeminatan($id)
    {
        $bidang = \App\Models\BidangPeminatan::findOrFail($id);
        $bidang->delete();
        return response()->json(['success' => true, 'message' => 'Bidang Peminatan berhasil dihapus']);
    }
}
