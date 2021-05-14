<?php

namespace App\Service;

class Response 
{
    public static function data($data)
    {
        return response()->json([
            'status' => true,
            'message' => 'success retrieved data',
            'data' => $data
        ], 200);
    }
    public static function invalidLogin()
    {
        return response()->json([
            'status' => false,
            'message' => 'username or password do not match or empty'
        ], 401);
    }
    public static function validation($errors = null)
    {
        return response()->json([
            'status' => false,
            'message' => $errors
        ], 422);
    }
    public static function successLogin($token, $role)
    {
        return response()->json([
            'status' => true,
            'message' => 'login success',
            'token' => $token,
            'role' => $role,
        ], 200);
    }
    public static function successLogout()
    {
        return response()->json([
            'status' => true,
            'message' => 'logout success'
        ], 200);
    }
    public static function unauthorizedUser()
    {
        return response()->json([
            'status' => false,
            'message' => 'unauthorized user'
        ], 401);
    }
    public static function forbiddenUser()
    {
        return response()->json([
            'status' => false,
            'message' => 'forbidden user access'
        ], 403);
    }
    public static function successMessage($message)
    {
        return response()->json([
            'status' => true,
            'message' => $message
        ], 200);
    }
    public static function failed($message)
    {
        return response()->json([
            'message' => $message
        ]);
    }
}