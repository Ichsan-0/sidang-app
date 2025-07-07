<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;
use Yajra\DataTables\Facades\DataTables;
use App\Models\TahunAjaran; 

class DataMaster extends Controller
{
    public function prodi()
    {
        return view('master.prodi');
    }
    public function ajaxProdi(Request $request)
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


}
