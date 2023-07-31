<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Utils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    use Utils;
    
    public function index()
    {
        return view('pages.content');
    }

    public function getContent()
    {
        $content = Company::first();
        return response()->json(['content' => $content], 200);
    }

    public function update(Request $request) 
    {
        
        try {
            // $data_arr = (array) $data;
            // $validator = Validator::make($data_arr, [
            //     'name' => 'required|min:3|string',
            //     'address' => 'required|min:3|string',
            //     'phone' => 'required',
            // ]);
            
            // if ($validator->fails()) {
            //     return response()->json(['error' => $validator->errors()], 422);
            // }
            
            $data = json_decode($request->content);
            $content = Company::find($data->id);

            $logoImage = $content->logo;
            if ($request->hasFile('logo')) {
                if (!empty($content->logo) && file_exists($content->logo)) 
                    unlink($content->logo);
                $logoImage = $this->imageUpload($request, 'logo', 'uploads/content');
            }

            $content->name = $data->name;
            $content->address = $data->address;
            $content->phone = $data->phone;
            $content->logo = $logoImage;
            $content->ip_address = $request->ip();
            $content->update_by = Auth::user()->id;
            $content->save();
            return response()->json(['message' => "Content Update successful."], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => "Opps! something went wrong"], 400);
        }
    }

}
