<?php

namespace Thotam\ThotamHr\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckInfo
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
        $hr = optional(optional(Auth::user())->hr);

        if (!!!optional($hr->mails)->count()) {
            return response()->view('thotam-hr::auth.update-more-info',[
                'title' => 'Thông tin cá nhân',
                'msg' => 'Vui lòng cập nhật Email để tiếp tục'
                ]);
        } else {
            return $next($request);
        }
    }
}
