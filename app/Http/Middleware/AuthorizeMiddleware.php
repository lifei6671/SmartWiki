<?php

namespace SmartWiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request ;

class AuthorizeMiddleware
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

        $member = session_member();

        if(empty($member)){
            return redirect(route('account.login'));
        }

        return $next($request);
    }
}
