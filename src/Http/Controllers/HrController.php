<?php

namespace Thotam\ThotamHr\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Thotam\ThotamHr\DataTables\AdminHrDataTable;

class HrController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(AdminHrDataTable $dataTable)
    {
        if (Auth::user()->hr->hasAnyPermission(["view-hr", "add-hr", "edit-hr", "delete-hr", "set-team-hr", "set-permission-hr"])) {
            return $dataTable->render('thotam-hr::hr', ['title' => 'Quản lý Nhân sự']);
        } else {
            return view('errors.dynamic', [
                'error_code' => '403',
                'error_description' => 'Không có quyền truy cập',
                'title' => 'Quản lý Nhân sự',
            ]);
        }
    }

    /**
     * info
     *
     * @return void
     */
    public function info()
    {
        return view('thotam-hr::auth.update-more-info', [
            'title' => 'Thông tin cá nhân',
        ]);
    }
}
