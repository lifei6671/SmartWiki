<?php

namespace SmartWiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request ;

/**
 * 超级管理员中间件
 * Class SuperMemberMiddleware
 * @package SmartWiki\Http\Middleware
 */
class SuperMemberMiddleware
{

    /**
     * Handle an incoming request.
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle($request, Closure $next)
    {
        $member = session_member();

        if (empty($member)) {
            return redirect(route('account.login'));
        }
        if ($member->group_level == 0) {
            return $next($request);
        }
        return abort(403);
    }
}
