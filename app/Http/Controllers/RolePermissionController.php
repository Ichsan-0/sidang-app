<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RolePermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all()->groupBy(function($item) {
            return explode('-', $item->name)[0]; // group by prefix (bank, tugas, etc)
        });
        return view('role-permission', compact('permissions'));
    }

    public function ajax()
    {
        $data = Role::withCount('permissions')->get();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('permissions_count', fn($row) => $row->permissions_count)
            ->addColumn('action', function($row){
                $btn = '<button class="btn btn-sm btn-success btnGivePermission" data-id="'.$row->id.'" data-name="'.$row->name.'" title="Kelola Permission">
                            <i class="bx bx-key"></i>
                        </button> ';
                
                $btn .= '<button class="btn btn-sm btn-warning btnEdit" data-id="'.$row->id.'" title="Edit">
                            <i class="bx bx-edit"></i>
                        </button> ';
                
                $btn .= '<button class="btn btn-sm btn-danger btnDelete" data-id="'.$row->id.'" title="Hapus">
                            <i class="bx bx-trash"></i>
                        </button>';
                
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'array'
        ]);

        $role = Role::create(['name' => $request->name]);
        if($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['success' => true, 'data' => $role]);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);
        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,'.$id,
            'permissions' => 'array'
        ]);

        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions ?? []);

        return response()->json(['success' => true, 'data' => $role]);
    }

    public function destroy($id)
    {
        Role::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function givePermission(Request $request, $id)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        $role = Role::findOrFail($id);
        $role->syncPermissions($request->permissions);

        return response()->json([
            'success' => true, 
            'message' => 'Permission berhasil diperbarui',
            'data' => $role->load('permissions')
        ]);
    }
}
