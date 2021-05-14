<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoginToken;
use Auth;
use Validator;
use App\Service\Response;
use Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) 
        {
            return Response::validation($validator->errors());
        }

        $result = Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ]);

        if (!$result) 
        {
            return Response::invalidLogin();
        }

        $user = Auth::user();
        $token = Hash::make($user->id);
        $loginToken = LoginToken::where('user_id', $user->id)->orWhere('token', $token);
        
        if (!$loginToken->exists()) 
        {
            $newToken = new LoginToken;
            $newToken->user_id = $user->id;
            $newToken->token = $token;
            $newToken->save();
        }
        else
        {
            $loginToken->update([
                'token' => Hash::make($user->id)
            ]);
        }

        $token = $loginToken->first()->token;

        return Response::successLogin($token, $user->role);
    }

    public function logout(Request $request)
    {
        $token = $request->get('token');

        $result = LoginToken::where('token', $token)->delete();

        return Response::successLogout();
    }
}
