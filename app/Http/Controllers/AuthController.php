<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $data = $request->all();

        $Validator = Validator::make(
            $data,
            [
                "email" => "required|email|unique:users,email",
                "password" => "required"
            ]
        );

        if ($Validator->fails()) {
            return ["error" => $Validator->messages()];
        }

        $data['password'] = Hash::make($data['password']);

        $id = User::insertGetId($data);
        $user = User::find($id);

        return json_encode($user);
    }

    public function login(Request $request)
    {
        $array = ["error" => ""];

        $token = Auth::attempt($request->all());
        if($token) {
            $array["token"] = $token;
        } else {
            $array["error"] = "Dados incorretos!";
        }

        return $array;
    }

    // CONEXÃO COM SANCTUM
    // public function login(Request $request)
    // {
    //     $array = ["error" => ""];

    //     $creds = $request->only("email", "password");

    //     if(Auth::attempt($creds)) {

    //         $user = User::where("email", $creds["email"])->first();

    //         $item = time().rand(0,9999);
    //         $token = $user->createToken($item)->plainTextToken;

    //         $array["token"] = $token;

    //     } else {
    //         $array = ["error" => "E-mail ou senha incorretos"];
    //     }


    //     return $array;
    // }

    // public function logout(Request $request) {
    //     $array = ["error" => ""];

    //     Auth::user()->tokens()->delete();

    //     return $array;
    // }

    public function loginFail()
    {
        return ["error" => "Usuário não logado!"];
    }
}
