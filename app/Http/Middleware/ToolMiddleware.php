<?php
/**
 * Created by PhpStorm.
 * User: lifeilin
 * Date: 2017/3/6 0006
 * Time: 8:52
 */

namespace SmartWiki\Http\Middleware;

use Closure;
use Illuminate\Http\Request ;

class ToolMiddleware
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
        if ($member->group_level == 2) {
            return abort(403);
        }
        return $next($request);
    }
}