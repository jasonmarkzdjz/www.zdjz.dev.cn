<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class UcenterAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $response = $next($request);
         config(['auth.providers.users.model' => \App\Bls\Ucenter\Model\User\UserModel::class]);//重要用于指定特定model！！！！
        if(!Auth::check()){
            return redirect()->route('ucenter.h5.user.login');
        }
        return $response;
    }
}
