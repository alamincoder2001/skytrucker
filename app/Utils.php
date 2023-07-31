<?php

namespace App;

trait Utils
{
    public function imageUpload($request, $name, $directory)
    {
        $doUpload = function ($image) use ($directory) {
            $name = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extention = $image->getClientOriginalExtension();
            $imageName = $name . '_' . uniqId() . '.' . $extention;
            $image->move($directory, $imageName);
            return $directory . '/' . $imageName;
        };

        if (!empty($name) && $request->hasFile($name)) {
            $file = $request->file($name);
            if (is_array($file) && count($file)) {
                $imagesPath = [];
                foreach ($file as $key => $image) {
                    $imagesPath[] = $doUpload($image);
                }
                return $imagesPath;
            } else {
                return $doUpload($file);
            }
        }

        return false;
    }

    public function generateCode($model, $prefix = '')
    {
        $code = "00001";
        $model = '\\App\\Models\\' . $model;
        $num_rows = $model::count();
        if ($num_rows != 0) {
            $newCode = $num_rows + 1;
            $zeros = ['0', '00', '000', '0000'];
            $code = strlen($newCode) > count($zeros) ? $newCode : $zeros[count($zeros) - strlen($newCode)] . $newCode;
        }
        return $prefix . $code;
    }

    public function dateFormat($date = '')
    {
        return !empty($date) ? date('Y-m-d', strtotime($date)) : null;
    }

    public function unlinkImages($image) 
    {
        if (is_array($image) && count($image)) {
            foreach ($image as $_image) {
                if (isset($_image) && file_exists($_image)) {
                    unlink($_image);
                }
            }
        } else {
            if (isset($image) && file_exists($image)) {
                unlink($image);
            }
        }
    }


    public function send_sms($mobile, $message) 
    {
        // $url = "http://mshastra.com/sendurlcomma.aspx";

        // $data = [
        //   "api_key" => "R60014025fdb2a098f3ba7.10414485",
        //   "type" => "unicode",
        //   "contacts" => $mobileNumber,
        //   "senderid" => "Big Soft",
        //   "msg" => $message,
        // ];

        // $ch = curl_init();

        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        // $response = curl_exec($ch);

        // curl_close($ch);

        // $errorCodes = [1002, 1003, 1004, 1005, 1006, 1007, 1008, 1009, 1010, 1011, 1012, 1013, 1014];

        // if (in_array($response, $errorCodes)) {

        //     return false;
        // }

        // return true;

        $url = 'http://mshastra.com/sendurlcomma.aspx';
        $postData = array(
            "user" => "C00615",
            "sender" => "BigTech Ltd",
            "pwd" => 'prince@123',
            "CountryCode" => '+880',
            "mobileno" => $mobile,
            "msgtext" => $message
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        return $result;
    }

    public function nestedToSingle(array $array)
    {
        $singleArray = [];

        foreach ($array as $item) {
            if (is_array($item)) {
                $singleArray = array_merge($singleArray, $this->nestedToSingle($item));
            } else {
                $singleArray[] = $item;
            }
        }

        return $singleArray;
    }
    
}