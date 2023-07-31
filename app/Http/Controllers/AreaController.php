<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AreaController extends Controller
{
    public function index() 
    {
        return view('pages.area');
    }

    public function getArea() 
    {
        $areas = Area::latest()->get();
        return response()->json(['areas' => $areas], 200);
    }

    public function store(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $area = new Area();
            $area->name = $request->name;
            $area->latitude = $request->latitude;
            $area->longitude = $request->longitude;
            $area->zip_code = $request->zip_code;
            $area->camera = $request->camera;
            $area->status = 'a';
            $area->added_by = Auth::user()->id;
            $area->ip_address = $request->ip();
            $area->save();
            return response()->json(['message' => "Area Added successful."], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function update(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }

            $area =  Area::find($request->id);
            $area->name = $request->name;
            $area->latitude = $request->latitude;
            $area->longitude = $request->longitude;
            $area->zip_code = $request->zip_code;
            $area->camera = $request->camera;
            $area->update_by = Auth::user()->id;
            $area->ip_address = $request->ip();
            $area->save();
            return response()->json(['message' => "Area Update successful."], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

    public function destroy(Request $request) 
    {
        try {
            $area = Area::find($request->id);
            $area->status = 'd';
            $area->save();
            $area->delete();
            return response()->json(['message' => "Area Delete successful."], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }
}
