<?php

namespace App\Http\Controllers;

use App\Models\Gaming;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GamingController extends Controller
{
    public function index() 
    {
        return view('pages.gaming.index');
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
            $gaming->added_by = Auth::user()->id;
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
        $curUserType = Auth::user()->type;
        $curUserId = Auth::user()->id;

        $gaming = Gaming::with('area:id,name')->where('status', 'a');

        if($curUserType != 'admin') {
            $gaming = $gaming->where('added_by', $curUserId);
        }
        
        if(isset($req->areaId) && $req->areaId  != '') {
            $gaming = $gaming->where('area_id', $req->areaId);
        }

        if(isset($dateForm) && $dateForm != '' && isset($dateTo) && $dateTo != '') {
            $gaming = $gaming->whereBetween('created_at', [$dateForm . " 00:00:00", $dateTo . " 23:59:59"]);
        }

        $gaming = $gaming->latest()->get();

        return response()->json(['gaming' => $gaming], 200);

    }

    public function gamingList()
    {
        return view('pages.gaming.list');
    }

    public function deleteGaming(Request $req)
    {
        try {
            $gaming = Gaming::find($req->id);

            $gaming->status = 'd';
            $gaming->save();
            $gaming->delete();
            return response()->json(['message' => "Gaming Delete successful."],201);
        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }
}
