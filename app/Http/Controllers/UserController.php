<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Barangay;
use App\Models\PersonalInfo;
use App\Models\Position;

class UserController extends Controller
{
    public function index()
    {
        $users = User::select('users.*', 'personal_infos.last_name', 'personal_infos.first_name', 'personal_infos.middle_name')
            ->join('personal_infos', 'personal_infos.user_id', 'users.id')
            ->with(['userRoles.role'])
            ->paginate(20);

        return view('admin.maintenance.users.index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        $_barangay = new Barangay;
        $_position = new Position;
        $barangays = $_barangay->getBarangays();
        return view('admin.maintenance.users.create', [
            'barangays' => $barangays,
            '_position' => $_position
        ]);
    }

    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
            // return $request;
            $validated = $request->validate([
                'username' => 'required|min:6|max:16|unique:users',
                'email' => 'required|max:150|unique:users',
                'user_type' => 'required|in:admin,internal',
                'password' => 'required',
                'middlename' => 'required',
                'lastname' => 'required',
                'firstname' => 'required',
                'address' => 'required',
                'gender' => 'required',
                'contact_no' => 'required',
                'position' => 'required'
            ]);
    
            $user = User::create([
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'usertype' => $request->input('user_type'),
                'password' => bcrypt($request->input('password')),
                'position' => $request->input('position'),
                'is_active_user' => 1,
            ]);
    
            PersonalInfo::create([
                'user_id' => $user->id,
                'first_name' => $request->input('firstname'),
                'middle_name' => $request->input('middlename'),
                'last_name' => $request->input('lastname'),
                'barangay_id' => $request->input('address'),
                'gender' => $request->input('gender'),
                'contact_no' => $request->input('contact_no'),
                'email' => $request->input('email'),
            ]);
    
            return Redirect::route('users.index')->with('success', 'Successfully added user.');
        });
    }

    public function show(string $id)
    {
        // code...
    }

    public function edit(string $id)
    {
        $_position = new Position;
        $user_id = Crypt::decrypt($id);
        $user = User::find($user_id);

        return view('admin.maintenance.users.edit', [
            'user' => $user,
            '_position' => $_position
        ]);
    }

    public function update(Request $request, string $id)
    {
        $user_id = Crypt::decrypt($id);

        $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'username' => 'required|min:6|max:16',
            'email' => 'required|max:150'
        ]);

        return DB::transaction(function () use ($request, $user_id) {
            User::find($user_id)->update([
                'email' => $request->input('email'),
                'username' => $request->input('username'),
                'is_active_user' => 1,
            ]);

            if ($request->has('position')) {
                User::find($user_id)->update([
                    'position' => $request->input('position'),
                ]);
            }

            PersonalInfo::where('user_id', $user_id)->update([
                'first_name' => $request->input('firstname'),
                'middle_name' => $request->input('middlename'),
                'last_name' => $request->input('lastname'),
                'gender' => $request->input('gender'),
            ]);

            return Redirect::route('users.index')->with('success', 'Successfully saved!');
        });
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

    public function apiSearch(Request $request) {

        $search = $request->keyword;
        $users_query = User::query()->select('users.*', 'personal_infos.last_name', 'personal_infos.first_name', 'personal_infos.middle_name', DB::raw("CONCAT(personal_infos.last_name, ' ', personal_infos.middle_name, ' ', personal_infos.first_name) AS text"))
            ->join('personal_infos', 'personal_infos.user_id', 'users.id')
            ->where('users.username', 'like', "%{$search}%")
            ->orWhere('personal_infos.last_name', 'like', "%{$search}%")
            ->orWhere('personal_infos.first_name', 'like', "%{$search}%")
            ->orWhere('personal_infos.middle_name', 'like', "%{$search}%");

        if($request->has('usertype')) {
            $users_query->whereIn('users.usertype', $request->usertype);
        } 

        return $users_query->get();
    }

    // DB: u522295882_owpms
    // User: u522295882_owpms
    // DB pw: 7kF+FAoDd
}
