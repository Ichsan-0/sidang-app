<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use Yajra\DataTables\Facades\DataTables; // Pastikan ini ada

class DataMaster extends Controller
{
    public function prodi()
    {
        return view('prodi');
    }

    public function ajax(Request $request)
    {
    if ($request->ajax()) {
        $data = Prodi::query();

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


}
