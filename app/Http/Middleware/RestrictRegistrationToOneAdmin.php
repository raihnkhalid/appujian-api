<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Helpers\JsonApi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RestrictRegistrationToOneAdmin
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
        $user = User::where('is_admin', 1)->first();
        // $user = DB::table('users')->select('is_admin')->where('id', 1)->first();
        if ($user && (int)$user->is_admin === 1) {
            return JsonApi::JsonApi(400, "Bad_Request", ["message" => "Cannot register as admin again!"]);
        }
        return $next($request);
    }
}
