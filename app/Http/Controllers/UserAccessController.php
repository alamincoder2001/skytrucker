<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Permission;
use App\Models\UserAccess;
use Exception;
use Illuminate\Http\Request;

class UserAccessController extends Controller
{
    public function permission_edit($id)
    {
        $user = User::find($id);
        $userAccess = UserAccess::where('user_id', $id)->pluck('permissions')->toArray();
        // dd($userAccess);
        $group_name = Permission::pluck('group_name')->unique();
        $permissions = Permission::all();
        return view('auth.permission', compact('user', 'userAccess', 'group_name', 'permissions'));
    }

    public function store_permission(Request $request)
    {
        $userAccess = UserAccess::where('user_id', $request->user_id)->delete();

        $permissions = Permission::all();

        try {
            foreach ($permissions as $key => $value) {
                if (in_array($value->id, $request->permissions)) {
                    UserAccess::create([
                        'user_id'     => $request->user_id,
                        'group_name'  => $value->group_name,
                        'permissions' => $value->permissions,
                    ]);
                }
            }
            return redirect()->route('user.registration')->with('success', 'Permissions added successfullly');
        } catch (Exception $e) {
            return redirect()->route('user.registration');
            return $e->getMessage();
        }
    }

}
