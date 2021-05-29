<?php

namespace Thotam\ThotamHr\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;

class CheckHR
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
        $hr = optional(Auth::user())->hr;
        if (!!!$hr) {
            return response()->view('thotam-hr::auth.update-info',[
                'title' => 'Đã có lỗi sảy ra...',
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'text_xlarge' => 'Tài khoản chưa được liên kết với thông tin nhân sự<br>Vui lòng cung cấp thông tin để được trợ giúp',
                ]);
        } elseif (optional($hr)->active === 0) {
            return response()->view('errors.dynamic',[
                'title' => 'Hồ sơ đã bị vô hiệu hóa',
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'text_xlarge' => 'Vui lòng liên hệ phòng truyền thông để được trợ giúp',
                ]);
        } elseif (!!!optional($hr)->active) {
            return response()->view('errors.dynamic',[
                'title' => 'Hồ sơ chưa được kích hoạt',
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'text_xlarge' => 'Vui lòng liên hệ phòng truyền thông để được trợ giúp',
            ]);
        } else {
            return $next($request);
        }
    }
}
