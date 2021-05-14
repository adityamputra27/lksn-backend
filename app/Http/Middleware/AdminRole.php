<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\LoginToken;
use App\Service\Response;

class AdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->get('token');

        $loginToken = LoginToken::where('token', $token);

        if ($loginToken->exists() && $loginToken->first()->user->role == 'admin') 
        {
            return $next($request);
        }

        return Response::forbiddenUser();

    }
}
