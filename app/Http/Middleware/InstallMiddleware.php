<?php

namespace SmartWiki\Http\Middleware;

use Closure;
use Route;

/**
 * 拦截系统是否安装的中间件
 * Class InstallMiddleware
 * @package SmartWiki\Http\Middleware
 */
class InstallMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = public_path('install.lock');


        $uri = $request->getRequestUri();


        if(stripos($uri,'/install') === false){

            if( file_exists($path) === false) {
                return redirect(route('install.index'));
            }
        }elseif(stripos($uri,'/install') === 0){
            if( file_exists($path)) {
                return redirect(route('member.index'));
            }
        }
        return $next($request);
    }
}
