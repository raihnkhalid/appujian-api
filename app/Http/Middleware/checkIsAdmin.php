<?php

namespace App\Http\Middleware;

use App\Helpers\AppHelpers;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class checkIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if($request->get('is_admin')){
            return $next($request);
        }

        return AppHelpers::JsonApi(401, "Unauthorized", ['message' => 'Invalid Access', 'cek' => $user]);

    }
}
