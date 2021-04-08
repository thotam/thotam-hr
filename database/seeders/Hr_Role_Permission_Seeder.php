<?php

namespace Thotam\ThotamHr\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Hr_Role_Permission_Seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permission[] = Permission::updateOrCreate([
            'name' => 'view-hr'
        ],[
            "description" => "Xem Nhân sự",
            "group" => "HR",
            "order" => 1,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'add-hr'
        ],[
            "description" => "Thêm Nhân sự",
            "group" => "HR",
            "order" => 2,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'edit-hr'
        ],[
            "description" => "Sửa Nhân sự",
            "group" => "HR",
            "order" => 3,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'delete-hr'
        ],[
            "description" => "Xóa Nhân sự",
            "group" => "HR",
            "order" => 4,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'set-team-hr'
        ],[
            "description" => "Set Team cho Nhân sự",
            "group" => "HR",
            "order" => 5,
            "lock" => true,
        ]);

        $permission[] = Permission::updateOrCreate([
            'name' => 'set-permission-hr'
        ],[
            "description" => "Set Quyền cho Nhân sự",
            "group" => "HR",
            "order" => 6,
            "lock" => true,
        ]);

        $super_admin = Role::updateOrCreate([
            'name' => 'super-admin'
        ],[
            "description" => "Super Admin",
            "group" => "Admin",
            "order" => 1,
            "lock" => true,
        ]);

        $admin = Role::updateOrCreate([
            'name' => 'admin'
        ],[
            "description" => "Admin",
            "group" => "Admin",
            "order" => 2,
            "lock" => true,
        ]);

        $admin_hr = Role::updateOrCreate([
            'name' => 'admin-hr'
        ],[
            "description" => "Admin HR",
            "group" => "Admin",
            "order" => 4,
            "lock" => true,
        ]);

        $super_admin->givePermissionTo($permission);
        $admin->givePermissionTo($permission);
        $admin_hr->givePermissionTo($permission);
    }
}
