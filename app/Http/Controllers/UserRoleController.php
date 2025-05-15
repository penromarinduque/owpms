<?php

namespace App\Http\Controllers;

use App\Models\UserRole;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class UserRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        if(!isset($request->role)){
            return redirect()->back()->with('error', 'Please select a role');
        }

        if(!isset($request->user_id)){
            return redirect()->back()->with('error', 'User not defined.');
        }

        if(UserRole::where('user_id', $request->user_id)->where('role_id', $request->role)->exists()){
            return redirect()->back()->with('error', 'User Role already exists.');
        }

        UserRole::create([
            'user_id' => $request->user_id,
            'role_id' => $request->role
        ]);

        return redirect()->back()->with('success', 'User Role added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserRole $userRole)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $user_id = Crypt::decryptString($id);

        $user = User::query()->with(['userRoles.role'])->find($user_id);
        $userRoles = $user->userRoles;

        return view('iam.userroles.edit',[
            'userRoles' => $userRoles,
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserRole $userRole)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $userRoleId = Crypt::decryptString($id);

        UserRole::find($userRoleId)->delete();

        return redirect()->back()->with("success", "User Role removed successfully.");
    }

    public function apiSearch(Request $request){
        $search = $request->search;

        $userRoles = Role::query()->select('user_roles.*', 'roles.id as role_id', 'roles.role_name as text');
        $userRoles = Role::query()
            ->where('role_name', 'like', '%' . $search . '%')
            ->where("is_active", 1);

        return response()->json($userRoles->get());
    }
}
