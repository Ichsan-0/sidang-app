<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles')->get();
        return view('user', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'role' => 'required|exists:roles,name',
            'prodi' => 'required|exists:prodis,id',
            'no_induk' => 'required|string|max:50|unique:users,no_induk',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'prodi_id' => $request->prodi,
            'no_induk' => $request->no_induk,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'password' => Hash::make($request->no_induk), // password default = NIP
        ]);
        $user->assignRole($request->role);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil ditambahkan (password default = NIP)']);
        }
        return redirect()->route('user.index')->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        if(request()->ajax()) {
            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'prodi' => $user->prodi_id,
                'no_induk' => $user->no_induk,
                'no_hp' => $user->no_hp,
                'alamat' => $user->alamat,
            ]);
        }
        return view('admin.user.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|exists:roles,name',
            'prodi' => 'required|exists:prodis,id',
            'no_induk' => 'required|string|max:50|unique:users,no_induk,'.$user->id,
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:6',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->prodi_id = $request->prodi;
        $user->no_induk = $request->no_induk;
        $user->no_hp = $request->no_hp;
        $user->alamat = $request->alamat;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        $user->syncRoles([$request->role]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil diupdate']);
        }
        return redirect()->route('user.index')->with('success', 'User berhasil diupdate');
    }

    public function ajax(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')->select('users.*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('roles', function ($row) {
                    return $row->getRoleNames()->implode(', ');
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
                ->make(true);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        if(request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'User berhasil dihapus']);
        }
        return redirect()->route('user.index')->with('success', 'User berhasil dihapus');
    }
}
