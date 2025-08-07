<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prodi;

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
        return view('tugas_akhir', [
            'prodi' => $prodi
        ]);
    }
}
