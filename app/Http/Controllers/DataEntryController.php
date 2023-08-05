<?php

namespace App\Http\Controllers;

use App\Utils;
use App\Models\Picture;
use App\Models\DataEntry;
use Illuminate\Http\Request;
use App\Exports\DataEntryExport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DataEntryController extends Controller
{
    use Utils;

    public function index()
    {
        return view('pages.data.index');
    }

    public function sendOtp($name, $mobile, $code)
    {
        $message = "Dear {$name},\nYour OTP Code is: {$code}\nThank you,\nSky Tracker";
        $res = $this->send_sms($mobile, $message);
        return $res;
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'name'             => 'required|min:3|string',
                'mobile'           => 'required|min:11',
                'new_sim'          => 'required',
                'bip_app_install'  => 'required',
                'app_install'      => 'required',
                'toffee'           => 'required',
                'sell_package'     => 'required',
                'recharge_package' => 'required',
                'area_id'          => 'required',
                // 'image' => 'mimes:jpg,png,jpeg,bmp'
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

            // $data->image = $this->imageUpload($request, 'image', 'uploads/data') ?? '';
            $data->ip_address = $request->ip();
            $data->status = 'a';
            $data->otp = $code;
            $data->added_by = Auth::user()->id;
            $data->save();
            // $this->sendOtp($data->name, $data->mobile, $code);
            // session(['verification_code' => $code]);
            return response()->json(['message' => "Data Successfully Saved!", 'mobile' => $data->mobile], 201);
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

            //code...
            $verificationCode = session('verification_code');

            if (isset($verificationCode)) {
                if ($verificationCode == $req->code) {
                    $data = DataEntry::where('otp', $req->code)->where('status', 'p')->first();
                    $data->status = 'a';
                    $data->save();

                    session()->forget(['verification_code']);
                    return response()->json(['message' => "Data Successfully Verified."], 201);
                } else {
                    return response()->json(['error' => "The verification code was incorrect."]);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function update(Request $request)
    {
    }

    public function dataList()
    {
        return view('pages.data.list');
    }

    public function areawiseDataList()
    {
        return view('pages.data.areawiselist');
    }

    public function teamleaderwisedataList()
    {
        return view('pages.data.teamleaderwiselist');
    }

    public function bpwisedataList()
    {
        return view('pages.data.bpwiselist');
    }

    public function getDataList(Request $request)
    {
        $dateForm = $request->dateFrom;
        $dateTo = $request->dateTo;

        $dataLists = DataEntry::where('status', 'a')->with('area');

        if (Auth::user()->type == 'bp') {
            $dataLists = $dataLists->where('added_by', Auth::user()->id);
        }

        if (isset($request->areaId) && $request->areaId != '') {
            $dataLists = $dataLists->where('area_id', $request->areaId);
        }

        if (isset($request->bpId) && $request->bpId != '') {
            $dataLists = $dataLists->where('added_by', $request->bpId);
        }

        if (isset($request->leaderId) && $request->leaderId != '') {
            $dataLists = $dataLists->whereHas('user', function ($q) use ($request) {
                $q->where('team_leader_id', $request->leaderId);
            });
        }

        if (isset($dateForm) && $dateForm != '' && isset($dateTo) && $dateTo != '') {
            $dataLists = $dataLists->whereBetween('created_at', [$dateForm . " 00:00:00", $dateTo . " 23:59:59"]);
        }

        $dataLists = $dataLists->latest()->get();

        return response()->json(['dataLists' => $dataLists], 200);
    }

    public function dataExport($dateForm, $dateTo, $areaId = 0, $leaderId = 0, $bpId = 0)
    {
        $filename = $dateForm . '-' . $dateTo . '-' . time() . '-datalist.xlsx';
        return (new DataEntryExport)->dataclause($dateForm, $dateTo, $areaId, $leaderId, $bpId)->download($filename);
    }

    public function takePicture()
    {
        return view('pages.picture.index');
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
            $picture->latitude =  Auth::user()->latitude;
            $picture->longitude =  Auth::user()->longitude;
            $picture->status = 'a';
            $picture->ip_address = $req->ip();
            $picture->added_by = Auth::user()->id;
            $picture->save();
            return response()->json(['message' => "Picture Added successful."], 201);
        } catch (\Exception $ex) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function getPictures(Request $req)
    {
        $dateForm = $req->dateFrom;
        $dateTo = $req->dateTo;
        $curUserType = Auth::user()->type;
        $curUserId = Auth::user()->id;

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

    public function pictureList()
    {
        return view('pages.picture.list');
    }

    public function deletePicture(Request $req)
    {
        try {
            $picture = Picture::find($req->id);
            if (!empty($picture->image) && file_exists($picture->image)) {
                unlink($picture->image);
            }

            $picture->status = 'd';
            $picture->save();
            $picture->delete();
            return response()->json(['message' => "Picture Delete successful."], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function getTotalDataList(Request $req)
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
}
