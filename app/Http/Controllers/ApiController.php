<?php

namespace App\Http\Controllers;

use App\Utils;
use App\Models\Area;
use App\Models\User;
use App\Models\Gaming;
use App\Models\Picture;
use App\Models\DataEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Location\Facades\Location;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    use Utils;

    public function register(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'type', 'team_leader_id', 'username', 'password');
        $validator = Validator::make($data, [
            'name' => 'required|string',
            'type' => 'required',
            'username' => 'required|string|unique:users',
            'password' => 'required|string|min:1|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is valid, create new user
        $user = User::create([
            'name' => $request->name,
            // 'email' => $request->email,
            'type' => $request->type,
            'username' => $request->username,
            'team_leader_id' => $request->team_leader_id,
            'status' => 'a',
            'password' => bcrypt($request->password)
        ]);

        //User created, return success response
        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data' => $user
        ], Response::HTTP_OK);
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

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');

        //valid credential
        $validator = Validator::make($credentials, [
            'username' => 'required',
            'password' => 'required|string|min:1|max:50'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        $ip = $request->ip();
        $userLocationInfo = Location::get($ip);

        $user = User::select('id', 'name', 'type', 'username', 'area_id', 'latitude', 'longitude')->with('area:id,name,latitude,longitude')->where('username', $request->username)->first();

        // $targetLatitude = $user->type == 'admin' ? $userLocationInfo->latitude : $user->area->latitude; 
        // $targetLongitude = $user->type == 'admin' ? $userLocationInfo->longitude : $user->area->longitude; 

        // $distance = $this->haversineDistance($userLocationInfo->latitude, $userLocationInfo->longitude, $targetLatitude, $targetLongitude);

        // User is within 2 kilometer of the target location
        // if ($distance <= 2.0) {
        //Request is validated
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login credentials are invalid.',
                ], 400);
            }
        } catch (JWTException $e) {
            // return $credentials;
            return response()->json([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        $user->latitude = $userLocationInfo->latitude;
        $user->longitude = $userLocationInfo->longitude;
        $user->save();

        //Token created, return with success response and jwt token
        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => $user,
        ]);
        // } else {
        //     // User is not at the target location
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Your are not at the target location.',
        //     ]);
        // }
    }

    public function logout(Request $request)
    {
        //valid credential
        $validator = Validator::make($request->only('token'), [
            'token' => 'required'
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 200);
        }

        //Request is validated, do logout        
        try {
            $user = User::find($request->id);
            $user->latitude = null;
            $user->longitude = null;
            $user->save();

            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User has been logged out'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        $user = JWTAuth::authenticate($request->token);

        return response()->json(['user' => $user]);
    }

    public function getAreas()
    {
        $areas = Area::where('status', 'a')->get();

        return response()->json(['areas' => $areas], 200);
    }

    public function getData(Request $req)
    {
        $dateFrom = $req->dateFrom;
        $dateTo   = $req->dateTo;

        $dataLists = DataEntry::where('status', 'a')->with('area:id,name');

        if (isset($req->userId) && $req->userId != '') {
            $dataLists = $dataLists->where('added_by', $req->userId);
        }

        if (isset($req->areaId) && $req->areaId != '') {
            $dataLists = $dataLists->where('area_id', $req->areaId);
        }

        if (isset($req->bpId) && $req->bpId != '') {
            $dataLists = $dataLists->where('added_by', $req->bpId);
        }

        if (isset($req->leaderId) && $req->leaderId != '') {
            $dataLists = $dataLists->whereHas('user', function ($q) use ($req) {
                $q->where('team_leader_id', $req->leaderId);
            });
        }

        if (isset($dateFrom) && $dateFrom != '' && isset($dateTo) && $dateTo != '') {
            $dataLists = $dataLists->whereBetween('created_at', [$dateFrom . " 00:00:00", $dateTo . " 23:59:59"]);
        }

        $dataLists = $dataLists->latest()->get();

        return response()->json(['dataLists' => $dataLists], 200);
    }

    public function getTotalData(Request $req)
    {
        $dateFrom = $req->dateFrom;
        $dateTo = $req->dateTo;
        $id = Auth::user()->id;
        $clauses = "";

        if (isset($dateFrom) && $dateFrom != '' && isset($dateTo) && $dateTo != '') {
            $clauses .= " AND date(de.created_at) BETWEEN '$dateFrom' AND '$dateTo'";
        }
        if (Auth::user()->type == 'bp') {
            $clauses .= " AND de.added_by = '$id'";
            $dataLists = DB::select("SELECT * FROM data_entries de WHERE de.status ='a' $clauses");
        } else if (Auth::user()->type == 'team_leader') {
            $users = User::where('team_leader_id', $id)->get();
            $dataLists = [];
            foreach ($users as $value) {
                $datas = DB::select("SELECT * FROM data_entries de WHERE de.status ='a' AND de.added_by = '$value->id' $clauses");
                foreach ($datas as $key => $val) {
                    $dataLists[$key] = $val;
                }
            }
        } else if (Auth::user()->type == 'admin') {
            $dataLists = DB::select("SELECT * FROM data_entries de WHERE de.status ='a' $clauses");
        } else {
            $clauses .= " AND de.added_by = '$id'";
            $dataLists = DB::select("SELECT * FROM data_entries de WHERE de.status ='a' $clauses");
        }

        $newsim = array_filter($dataLists, function ($row) {
            return $row->new_sim == 'yes';
        });
        $appinstall = array_filter($dataLists, function ($row) {
            return $row->app_install == 'yes';
        });
        $bipappinstall = array_filter($dataLists, function ($row) {
            return $row->bip_app_install == 'yes';
        });
        $toffeegift = array_filter($dataLists, function ($row) {
            return $row->toffee_gift == 'yes';
        });

        $recharge = array_filter($dataLists, function ($row) {
            return $row->recharge_package == 'yes';
        });

        $rechargeamount = array_sum(array_map(function ($data) {
            return $data->recharge_amount;
        }, $recharge));

        $voice = array_filter($dataLists, function ($row) {
            return $row->voice == 'yes';
        });

        $voiceamount = array_sum(array_map(function ($data) {
            return $data->voice_amount;
        }, $voice));


        $res = [
            'newsim'        => count($newsim),
            'appinstall'    => count($appinstall),
            'bipappinstall' => count($bipappinstall),
            'toffeegift'    => count($toffeegift),
            'rechargeamount' => $rechargeamount,
            'voiceamount'   => $voiceamount,
        ];

        return response()->json($res, 200);
    }

    public function sendOtp($name, $mobile, $code)
    {
        $message = "Dear {$name},\nYour OTP Code is: {$code}\nThank you,\nSky Tracker";
        $res = $this->send_sms($mobile, $message);
        return $res;
    }

    public function dataStore(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name'             => 'required|min:3|string',
                'mobile'           => 'required|min:11',
                'new_sim'          => 'required',
                'app_install'      => 'required',
                'bip_app_install'  => 'required',
                'toffee'           => 'required',
                'sell_package'     => 'required',
                'recharge_package' => 'required',
                'area_id'          => 'required',
                // 'image' => 'mimes:jpg,jpeg,png,bmp'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $errors = $this->nestedToSingle($errors);
                return response()->json(['error' => $errors], 422);
            }

            $exitsCheck = DataEntry::where('mobile', $request->mobile)->where('status', 'a')->first();
            if ($exitsCheck) {
                return response()->json(['error' => "The Mobile number already exit our record!"]);
            }

            $code = mt_rand(1000, 9999);
            $data = new DataEntry();

            $dataKeys = $request->except('image');
            foreach ($dataKeys as $key => $item) {
                $data->$key = $request->$key;
            }

            // $data->image = $this->imageUpload($request, 'image', 'uploads/data');
            $data->ip_address = $request->ip();
            $data->status = 'a';
            $data->otp = $code;
            // $data->added_by = 1;
            $data->save();
            // $this->sendOtp($data->name, $data->mobile, $code);
            return response()->json(['message' => "Data Successfully Saved!", 'mobile' => $data->mobile, 'otpCode' => $code], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function phoneVerifyProcess(Request $req)
    {
        try {

            $validator = Validator::make($req->all(), [
                'code' => 'required|min:4|max:4'
            ], [
                'code.min' => 'The code must be at least 4 digits.',
                'code.max' => 'The code may not be greater than 4 digits.'
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->toArray();
                $errors = $this->nestedToSingle($errors);
                return response()->json(['error' => $errors], 422);
            }

            if ($req->otpCode == $req->code) {
                $data = DataEntry::where('otp', $req->code)->where('status', 'p')->first();
                $data->status = 'a';
                $data->save();

                return response()->json(['message' => "Data Successfully Verified."], 200);
            } else {
                return response()->json(['error' => "The OTP code is incorrect."], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function getTypeWiseUser(Request $req)
    {
        $users = User::where('status', 'a');

        if (isset($req->userType) && $req->userType != '') {
            $users = $users->where('type', $req->userType);
        }

        $users = $users->get();

        return response()->json(['users' => $users], 200);
    }

    public function savePicture(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'image' => 'required|mimes:jpg,jpeg,png,bmp'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $picture = new Picture();
            $picture->image = $this->imageUpload($req, 'image', 'uploads/picture');
            $picture->latitude =  $req->latitude;
            $picture->longitude =  $req->longitude;
            $picture->status = 'a';
            $picture->ip_address = $req->ip();
            $picture->added_by = $req->added_by;
            $picture->save();
            return response()->json(['message' => "Picture Added successful."], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function getPicture(Request $req)
    {
        $dateForm = $req->dateFrom;
        $dateTo = $req->dateTo;
        $curUserType = $req->curUserType;
        $curUserId = $req->curUserId;

        $pictures = Picture::with('user:id,name')->where('status', 'a');

        if ($curUserType != 'admin') {
            $pictures = $pictures->where('added_by', $curUserId);
        }

        if (isset($req->userId) && $req->userId  != '') {
            $pictures = $pictures->where('added_by', $req->userId);
        }

        if (isset($dateForm) && $dateForm != '' && isset($dateTo) && $dateTo != '') {
            $pictures = $pictures->whereBetween('created_at', [$dateForm . " 00:00:00", $dateTo . " 23:59:59"]);
        }

        $pictures = $pictures->latest()->get();

        return response()->json(['pictures' => $pictures], 200);
    }

    public function saveGaming(Request $req)
    {
        try {
            $validator = Validator::make($req->all(), [
                'name' => 'required|string|min:3',
                'mobile' => 'required',
                'area_id' => 'required',
                'my_bl' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $gaming = new Gaming();
            $gaming->name =  $req->name;
            $gaming->mobile =  $req->mobile;
            $gaming->area_id =  $req->area_id;
            $gaming->my_bl =  $req->my_bl;
            $gaming->gift =  $req->gift;
            $gaming->status = 'a';
            $gaming->ip_address = $req->ip();
            $gaming->added_by = $req->added_by;
            $gaming->save();
            return response()->json(['message' => "Gaming Added successful."], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function getGaming(Request $req)
    {
        $dateForm = $req->dateFrom;
        $dateTo = $req->dateTo;
        $curUserType = $req->curUserType;
        $curUserId = $req->curUserId;

        $gaming = Gaming::with('area:id,name')->where('status', 'a');

        if ($curUserType != 'admin') {
            $gaming = $gaming->where('added_by', $curUserId);
        }

        if (isset($req->areaId) && $req->areaId  != '') {
            $gaming = $gaming->where('area_id', $req->areaId);
        }

        if (isset($dateForm) && $dateForm != '' && isset($dateTo) && $dateTo != '') {
            $gaming = $gaming->whereBetween('created_at', [$dateForm . " 00:00:00", $dateTo . " 23:59:59"]);
        }

        $gaming = $gaming->latest()->get();

        return response()->json(['gaming' => $gaming], 200);
    }
}
