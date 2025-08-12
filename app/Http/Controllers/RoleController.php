<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;                                                
use App\Models\Permission;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class RoleController extends Controller
{
    //
    public function index() {
        $roles = Role::query()->orderBy('role_name', 'ASC')->paginate(20);

        return view('iam.roles.index', [
            'roles' => $roles,
        ]);
    }


    public function create () {
        $permissions = Permission::query()->orderBy('permission_group', 'ASC')->get();

        return view('iam.roles.create', [
            'permissions' => $permissions
        ]);
    }

    public function store (Request $request) {
        //
        return DB::transaction(function () use ($request) {
            $request->validate([
                'role_name' => 'required',
                'role_description' => 'required',
                'permissions' => 'required'
            ]);

            $role = Role::create([
                'role_name' => $request->role_name,
                'role_description' => $request->role_description
            ]);

            RolePermission::insert(array_map(function ($permission) use ($role) {
                return [
                    'role_id' => $role->id,
                    'permission' => $permission
                ];
            }, $request->permissions));
    
            return redirect()->route('iam.roles.index')->with("success", "Role created successfully");
        });
    }
    
    public function edit(string $id) {
        $role_id = Crypt::decryptString($id);
        $role = Role::find($role_id);
        $role_permissions = RolePermission::where('role_id', $role_id)->pluck('permission')->toArray();
        $permissions = Permission::query()->orderBy('permission_group', 'ASC')->get();

        return view('iam.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'role_permissions' => $role_permissions
        ]);
    }

    public function update(string $id, Request $request) {
        //
        return DB::transaction(function () use ($request, $id) {
            $request->validate([
                'role_name' => 'required|max:50',
                'role_description' => 'required|max:500',
                'permissions' => 'required',
                'is_active' => 'required'
            ]);

            $role_id = Crypt::decryptString($id);
            $role = Role::find($role_id);
            $role->update([
                'role_name' => $request->role_name,
                'role_description' => $request->role_description,
                'is_active' => $request->is_active  
            ]);

            RolePermission::where('role_id', $role_id)->delete();
            RolePermission::insert(array_map(function ($permission) use ($role) {
                return [
                    'role_id' => $role->id,
                    'permission' => $permission,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }, $request->permissions));

            return redirect()->back()->with("success", "Role updated successfully");
        });
    }

    public function delete (string $id) {
        //
        $role_id = Crypt::decryptString($id);
        $role = Role::find($role_id);
        if(UserRole::where('role_id', $role_id)->exists()) {
            return redirect()->back()->with('error', 'Role is in use.');
        }
        $role->delete();
        return redirect()->route('iam.roles.index')->with("success", "Role deleted successfully");
    }
    
}

