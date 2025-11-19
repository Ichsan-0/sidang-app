<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use App\Models\DataAkhir;

class DataAkhirController extends Controller
{
    public function showPinAkhir()
    {
        return view('data-akhir');
    }
    public function ajaxPinAkhir()
    {
        $data = DataAkhir::all();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row){
                return '<div class="dropdown">
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
            ->make(true);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nim' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'nomor_transkrip' => 'required|string|max:255',
            'pin_ijazah' => 'required|string|max:255',
        ]);
        $row = DataAkhir::create($data);
        return response()->json(['success' => true, 'data' => $row]);
    }

    public function show($id)
    {
        $row = DataAkhir::findOrFail($id);
        return response()->json($row);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'nim' => 'required|numeric',
            'nama' => 'required|string|max:255',
            'nomor_transkrip' => 'required|string|max:255',
            'pin_ijazah' => 'required|string|max:255',
        ]);
        $row = DataAkhir::findOrFail($id);
        $row->update($data);
        return response()->json(['success' => true, 'data' => $row]);
    }

    public function destroy($id)
    {
        DataAkhir::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
