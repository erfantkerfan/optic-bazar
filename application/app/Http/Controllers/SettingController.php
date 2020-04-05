<?php

namespace App\Http\Controllers;

use App\setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public static function update_package_optien($key , $value){

        $request = setting::where('key', $key)->first();
        if($request){

            $request->val = $value;
            $request->save();

            return $request->id;

        }else{

            $requestNew = new setting;
            $requestNew->key = $key;
            $requestNew->val = $value;
            $requestNew->save();

            return $requestNew->id;

        }

    }

    public static function get_package_optien($key){

        $request = setting::where('key', $key)->first();
        if($request) return $request->val;

        return false;

    }

}
