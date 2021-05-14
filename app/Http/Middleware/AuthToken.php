<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\LoginToken;
use App\Service\Response;

class AuthToken
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

        $find = LoginToken::where('token', $token)->first();

        if (!$find || !$token) 
        {
            return Response::unauthorizedUser();
        }

        $user = User::find($find->user_id);

        Auth::login($user);

        return $next($request);
    }
}
