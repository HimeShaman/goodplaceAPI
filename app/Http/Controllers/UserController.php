<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function logout(){
        Auth::logout();
    }

    public function register(Request $request){

        $validator = Validator::make($request->all(),
            [
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
                'first_name' => ['string'],
                'last_name' => ['string'],
                'is_volunteer' => ['boolean']
            ]);

        if($validator->fails()) {
            return response()->json([
                "error" => 400,
                "message" => $validator->errors()->all()
            ],400);
        }

      $user = new User;
      $user->email = $request->email;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->password = Hash::make($request->password);
      $user->is_volunteer = $request->is_volunteer;
      $user->api_token = Str::random(60);
      $user->save();
      return $user;
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(),
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string']
            ]);
        if($validator->fails()) {
            return response()->json([
                "error" => 400,
                "message" => $validator->errors()->all()
            ],400);
        }

        $dbUser = User::where('email',$request->email)
        ->first();

        if(!empty($dbUser) && Hash::check($request->password,$dbUser->password)){
            return $dbUser;
        }else{
            return 'mabite';
        }



    }
}
