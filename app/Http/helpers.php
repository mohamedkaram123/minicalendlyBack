<?php

use Carbon\Carbon;
use Faker\Core\Number;
use Illuminate\Http\Exceptions\HttpResponseException;

if (!function_exists('success')) {
    function success($msg = "success",$data=[])
    {
        return  response()->json([
            "msg"=>$msg,
            "data"=>$data
        ]);
    }
}


if (!function_exists('fail')) {
    function fail($msg = "error",$data=[],$status_fail=400)
    {
        throw new HttpResponseException(
            response()->json(["msg"=>$msg,'data' => $data], $status_fail)
        );

    }
}

if (!function_exists('api_trans')) {
    function api_trans($key)
    {
        trans("api.$key",[],request()->header("Accept-Language"));

    }
}


if (!function_exists('fail_validate')) {
    function fail_validate($validate)
    {
        return fail("error",$validate->errors(),301);
    }
}



if (!function_exists('add_duration')) {
    function add_duration(Carbon $start_date, $durantion, $duration_type="m"):Carbon
    {
        if($duration_type == "m"){
           $end_Date =  $start_date->addMinutes($durantion);
        }else{
            $end_Date =  $start_date->addHours($durantion);

        }
       return $end_Date;
    }
}

?>
