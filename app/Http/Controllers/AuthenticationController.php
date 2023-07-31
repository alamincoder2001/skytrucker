<?php

namespace App\Http\Controllers;

use App\Utils;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;

class AuthenticationController extends Controller
{
    use Utils;
    
    public function login()
    {
        return view('auth.login');
    }

    public function loginCheck(Request $request) 
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:1',
        ]);
        try {
            $ip = $request->ip();  //for live server
            $userLocationInfo = Location::get($ip);

            // $reqUser = User::with('area')->where('username', $request->username)->first();
            // $targetLatitude = $reqUser->type == 'admin' ? $userLocationInfo->latitude : $reqUser->area->latitude; 
            // $targetLongitude = $reqUser->type == 'admin' ? $userLocationInfo->longitude : $reqUser->area->longitude; 

            // $distance = $this->haversineDistance($userLocationInfo->latitude, $userLocationInfo->longitude, $targetLatitude, $targetLongitude);

            // if ($distance <= 2.0) {
                // User is within 2 kilometer of the target location
                $Cradentials = $request->only('username', 'password');
                if(Auth::attempt($Cradentials)) {
                    $user = User::where('username', $request->username)->first();
                    $user->latitude = $userLocationInfo->latitude;
                    $user->longitude = $userLocationInfo->longitude;
                    $user->save();
                    Session::put('userLocationInfo', $userLocationInfo);
                    return redirect()->intended('/dashboard');
                }
                return redirect()->back()->withInput($request->only('username'))
                ->with('error', 'Username or Password was invalid.');
            // } else {
            //     // User is not at the target location
            //     return Redirect()->back()->with('error', 'Your are not at the target location.');
            // }
            
        } catch (\Exception $e) {
            return Redirect()->back()->with('error', 'Opps! something went wrong'. $e->getMessage());
        }
    }

    private function haversineDistance($latitude1, $longitude1, $latitude2, $longitude2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        // Convert latitude and longitude to radians
        $latFrom = deg2rad($latitude1);
        $lonFrom = deg2rad($longitude1);
        $latTo = deg2rad($latitude2);
        $lonTo = deg2rad($longitude2);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) + cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));

        return $angle * $earthRadius;
    }

    public function logout(Request $request) {
        $user = User::find(Auth::id());
        $user->latitude = null;
        $user->longitude = null;
        $user->save();
        Auth::logout();
        Session::flush();
        return redirect('/');
    }

    public function registration() 
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|string',
                // 'email' => 'required|email|unique:users',
                'type' => 'required',
                'username' => 'required|unique:users',
                'password' => 'required|min:1',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $user = new User();
            $user->name = $request->name;
            // $user->email = $request->email;
            $user->type = $request->type;
            $user->team_leader_id = $request->team_leader_id;
            $user->area_id = $request->area_id;
            $user->username = $request->username;
            $user->password = bcrypt($request->password);
            $user->image = $this->imageUpload($request, 'image', 'uploads/user');
            $user->status = 'a';
            $user->added_by = Auth::user()->id;
            $user->save();
            return response()->json(['message' => "User Create successful."],201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong".$e->getMessage()], 400);
        }
    }

    public function getUser(Request $request)
    {
        $users = User::with('teamLeader:id,name', 'area:id,name');

        if(isset($request->leaderId) && $request->leaderId  != '') {
            $users = $users->where('team_leader_id', $request->leaderId);
        }

        if(isset($request->addedBy) && $request->addedBy  != '') {
            $users = $users->where('added_by', $request->addedBy);
        }

        if(isset($request->userType) && $request->userType  != '') {
            $users = $users->where('type', $request->userType);
        }

        $users = $users->latest()->get();

        return response()->json(['users' => $users], 200);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|string',
                // 'email' => 'required|email|unique:users,email,'.$request->id,
                'type' => 'required',
                'username' => 'required|unique:users,username,'.$request->id,
                // 'password' => 'required|min:1',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $user = User::find($request->id);

            $userImage = $user->image;
            if ($request->hasFile('image')) {
                if (!empty($user->image) && file_exists($user->image)) unlink($user->image);
                $userImage = $this->imageUpload($request, 'image', 'uploads/user');
            } 

            $user->name = $request->name;
            // $user->email = $request->email;
            $user->type = $request->type;
            $user->team_leader_id = $request->team_leader_id;
            $user->area_id = $request->area_id;
            $user->username = $request->username;
            $user->password = ($request->password == null || $request->password == '') ? $user->password : bcrypt($request->password);
            $user->image = $userImage;
            $user->update_by = Auth::user()->id;
            $user->save();
            return response()->json(['message' => "User Update successful."],201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::find($request->id);
            if (!empty($user->image) && file_exists($user->image)) {
                unlink($user->image);
            }

            $user->status = 'd';
            $user->save();
            $user->delete();
            return response()->json(['message' => "User Delete successful."],201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function UserList()
    {
        return view('auth.users');
    }
}
