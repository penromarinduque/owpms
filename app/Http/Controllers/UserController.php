<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('users.*', 'personal_infos.last_name', 'personal_infos.first_name', 'personal_infos.middle_name')
            ->join('personal_infos', 'personal_infos.user_id', 'users.id')
            ->where('usertype', '<>', 1)
            ->with(['userRoles.role'])
            ->paginate(20);

        return view('admin.maintenance.users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return view('admin.maintenance.users.create');
    }

    public function store(Request $request)
    {
        // return $request;
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|min:6|max:16|unique:users',
            'email' => 'required|max:150|unique:users',
            'password' => 'required',
        ]);

        User::create([
            'name' => $request->input('firstname').' '.$request->input('lastname'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'password' => bcrypt($request->input('password')),
            'usertype' => 'admin',
            'is_active_user' => 1,
        ]);
        return Redirect::route('users.index')->with('success', 'Successfully saved!');
    }

    public function show(string $id)
    {
        // code...
    }

    public function edit(string $id)
    {
        $user_id = Crypt::decrypt($id);
        $user = User::find($user_id);

        return view('admin.maintenance.users.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user_id = Crypt::decrypt($id);

        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|min:6|max:16',
            'email' => 'required|max:150',
        ]);

        User::find($user_id)->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'username' => $request->input('username'),
            'is_active_user' => 1,
        ]);
        return Redirect::route('users.index')->with('success', 'Successfully saved!');
    }

    public function ajaxUpdateStatus(Request $request)
    {
        $user_id = Crypt::decrypt($request->user_id);
        $is_active_user = $request->is_active_user;

        $update = User::find($user_id)->update(['is_active_user'=>$is_active_user]);
        if ($update) {
            return Response()->json(['success'=>'Updated']);
        } else {
           return Response()->json(['failed'=>'Failed']); 
        }
    }

    // DB: u522295882_owpms
    // User: u522295882_owpms
    // DB pw: 7kF+FAoDd
}
