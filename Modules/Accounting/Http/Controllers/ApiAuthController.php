<?php

namespace Modules\Accounting\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $validate =Validator::make($request->all(),[
            'email' => 'required|string|email',
            //'password' => 'required|string',
        ],[
            "email.required"=>  trans("api.email_required",[],request()->header("Accept-Language")),
            //"password.required"=>  trans("api.password_required",[],request()->header("Accept-Language")),

        ]);
        if($validate->fails()){
            return fail("error",$validate->errors(),301);
        }
        $request->request->add(["password"=>"123456"]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return fail("error",$validate->errors()->add("email",trans("api.email_not_registerd",[],request()->header("Accept-Language"))),301);

        }

        $user = Auth::user();
        $user["token"] = $user->generateNewToken()['token'];

        return success("success",$user);

    }

    public function register(Request $request){


        $validate =Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
         //   'password' => 'required|string|min:6',
        ]);
        if($validate->fails()){
            return fail("error",$validate->errors(),301);
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'google_res' => json_encode($request->google_res) ,

            'password' => Hash::make("123456"),
        ]);

        $token = Auth::login($user);
        $user["token"] = $user->generateNewToken()['token'];
        return success("success",$user);

    }


    public function logout()
    {
        auth("api")->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }




    public function update_res_google(Request $req)
    {
        $user = User::find($req->id);

        $res = $this->make_expire_date($req->google_res);
        $user->google_res = json_encode($res);
        $user->save();
        $token = Auth::login($user);
        $user["token"] = $user->generateNewToken()['token'];
        return response()->json([
            'status' => 'success',
            'user' => new UserCollection($user)
        ]);
    }


public function make_expire_date($res)
{
    $res["expire_date"] = (new Carbon(now()))->addSeconds($res["expires_in"]);
    return $res;
}

}
