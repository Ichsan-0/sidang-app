<?php

namespace App\Http\Controllers;

use App\Models\BankJudul;
use Illuminate\Http\Request;

class BankJudulController extends Controller
{
    public function index()
    {
        return view('bank_judul.index');
    }

    public function list()
    {
        return response()->json(BankJudul::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'prodi_id' => 'nullable|integer',
            'bidang_peminatan_id' => 'nullable|integer',
            'status' => 'required|in:aktif,nonaktif',
        ]);
        $data['created_by'] = auth()->id();
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
