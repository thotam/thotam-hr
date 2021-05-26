<?php

namespace Thotam\ThotamHr\Http\Livewire;

use Auth;
use Livewire\Component;
use Thotam\ThotamHr\Models\HR;
use Spatie\Permission\Models\Role;
use Thotam\ThotamTeam\Models\Nhom;
use Thotam\ThotamHr\Jobs\HR_Sync_Job;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Permission;
use Thotam\ThotamTeam\Traits\HasNhomTrait;
use Thotam\ThotamTeam\Jobs\Nhom_Sync_Job;

class HrLivewire extends Component
{
    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $key, $hoten, $ten, $ngaysinh, $ngaythuviec, $active, $old_key, $old_hr, $new_hr, $teams;
    public $modal_title, $toastr_message;
    public $permissions, $roles, $permission_arrays, $role_arrays, $team_arrays;
    public $hr;

    /**
     * @var bool
     */
    public $addStatus = false;
    public $viewStatus = false;
    public $editStatus = false;
    public $setPermissionStatus = false;
    public $setTeamStatus = false;


    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method', 'add_hr', 'edit_hr', 'set_permission_hr', 'set_team_hr'];

    /**
     * dynamic_update_method
     *
     * @return void
     */
    public function dynamic_update_method()
    {
        $this->dispatchBrowserEvent('dynamic_update');
    }

    /**
     * On updated action
     *
     * @param  mixed $propertyName
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    /**
     * Validation rules
     *
     * @var array
     */
    protected function rules() {
        return [
            'key' => 'required|string|max:10|unique:Thotam\ThotamHr\Models\HR,key,'.$this->old_key,
            'hoten' => 'required|string|max:255',
            'ten' => 'required|string|max:50',
            'ngaysinh' => 'nullable|date_format:d-m-Y',
            'ngaythuviec' => 'nullable|date_format:d-m-Y',
            'active' => 'nullable|boolean',
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,name',
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'key' => 'mã nhân sự',
        'hoten' => 'họ tên',
        'ten' => 'tên',
        'ngaysinh' => 'ngày sinh',
        'ngaythuviec' => 'ngày thử việc',
        'active' => 'kích hoạt',
    ];

    public function updatedHoten()
    {
        $this->hoten = mb_convert_case(trim($this->hoten), MB_CASE_TITLE, "UTF-8");
        $names = explode(' ', $this->hoten);
        $this->ten = array_pop($names);
    }

    /**
     * cancel
     *
     * @return void
     */
    public function cancel()
    {
        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('hide_modals');
        $this->reset();
        $this->addStatus = false;
        $this->editStatus = false;
        $this->viewStatus = false;
        $this->setPermissionStatus = false;
        $this->setTeamStatus = false;
        $this->resetValidation();
        $this->mount();
    }

    /**
     * mount data
     *
     * @return void
     */
    public function mount()
    {
        $this->hr = Auth::user()->hr;
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('thotam-hr::livewire.hr.hr-livewire');
    }

    /**
     * add_hr
     *
     * @return void
     */
    public function add_hr()
    {
        if ($this->hr->cannot("add-hr")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->addStatus = true;
        $this->modal_title = "Thêm nhân sự mới";
        $this->toastr_message = "Thêm nhân sự mới thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#add_edit_modal");
    }

    /**
     * edit_hr
     *
     * @param  mixed $old_hr
     * @return void
     */
    public function edit_hr(HR $old_hr)
    {
        if ($this->hr->cannot("edit-hr")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->old_hr = $old_hr;
        $this->new_hr = $old_hr;

        $this->old_key = $this->old_hr->key;
        $this->key = $this->new_hr->key;
        $this->hoten = $this->new_hr->hoten;
        $this->ten = $this->new_hr->ten;
        $this->ngaysinh = optional($this->new_hr->ngaysinh)->format("d-m-Y");
        $this->ngaythuviec = optional($this->new_hr->ngaythuviec)->format("d-m-Y");
        $this->active = !!$this->new_hr->active;

        $this->editStatus = true;
        $this->modal_title = "Chỉnh sửa nhân sự";
        $this->toastr_message = "Chỉnh sửa nhân sự thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#add_edit_modal");
    }

    /**
     * save_hr
     *
     * @return void
     */
    public function save_hr()
    {
        if ($this->hr->cannot("add-hr") && $this->hr->cannot("edit-hr")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        $this->updatedHoten();

        if ($this->editStatus) {
            if (($this->hr->key == $this->old_hr->key) && ($this->key != $this->old_key)) {
                $this->dispatchBrowserEvent('unblockUI');
                $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không thể chỉnh sửa mã nhân sự của chính bản thân mình"]);
                return null;
            }
        }

        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'key' => 'required|string|max:10|unique:Thotam\ThotamHr\Models\HR,key,'.$this->old_key,
            'hoten' => 'required|string|max:255',
            'ten' => 'required|string|max:50',
            'ngaysinh' => 'nullable|date_format:d-m-Y',
            'ngaythuviec' => 'nullable|date_format:d-m-Y',
            'active' => 'nullable|boolean',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            if ($this->addStatus) {
                $hr = HR::create([
                    "key" => $this->key,
                    "hoten" => $this->hoten,
                    "ten" => $this->ten,
                    "ngaysinh" => $this->ngaysinh,
                    "active" => true,
                    "ngaythuviec" => $this->ngaythuviec,
                ]);

                HR_Sync_Job::dispatch($hr);
            } elseif ($this->editStatus) {
                if ($this->key == $this->old_key) {
                    $this->new_hr->update([
                        "key" => $this->key,
                        "hoten" => $this->hoten,
                        "ten" => $this->ten,
                        "ngaysinh" => $this->ngaysinh,
                        "ngaythuviec" => $this->ngaythuviec,
                        "active" => !!$this->active,
                    ]);
                } else {
                    $old_permissions = $this->old_hr->permissions;
                    $old_roles = $this->old_hr->roles;

                    $this->new_hr->syncPermissions([]);
                    $this->new_hr->syncRoles([]);

                    $this->new_hr->update([
                        "key" => $this->key,
                        "hoten" => $this->hoten,
                        "ten" => $this->ten,
                        "ngaysinh" => $this->ngaysinh,
                        "ngaythuviec" => $this->ngaythuviec,
                        "active" => !!$this->active,
                    ]);

                    $this->new_hr->syncPermissions($old_permissions);
                    $this->new_hr->syncRoles($old_roles);
                }
                HR_Sync_Job::dispatch($this->new_hr);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }

    /**
     * set_permission_hr
     *
     * @param  mixed $hr
     * @return void
     */
    public function set_permission_hr(HR $hr)
    {
        if ($this->hr->cannot("set-permission-hr")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->new_hr = $hr;
        $this->key = $this->new_hr->key;
        $this->hoten = $this->new_hr->hoten;
        $this->ten = $this->new_hr->ten;
        $this->ngaysinh = optional($this->new_hr->ngaysinh)->format("d-m-Y");
        $this->ngaythuviec = optional($this->new_hr->ngaythuviec)->format("d-m-Y");

        if (trait_exists(HasRoles::class)) {
            $this->permissions = $this->new_hr->permissions()->pluck("name", "id")->toArray();
            $this->roles = $this->new_hr->roles()->pluck("name", "id")->toArray();
            $this->permission_arrays = Permission::all()->groupBy("group")->toArray();
            $this->role_arrays = Role::all()->groupBy("group")->toArray();
        } else {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Không tồn tại chức năng phân quyền"]);
            $this->cancel();
            return null;
        }

        $this->setPermissionStatus = true;
        $this->modal_title = "Set quyền nhân sự";
        $this->toastr_message = "Set quyền nhân sự thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#set_permission_modal");
    }

    /**
     * save_set_permission_hr
     *
     * @return void
     */
    public function save_set_permission_hr()
    {
        if ($this->hr->cannot("set-permission-hr")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'nullable|exists:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|exists:permissions,name',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->new_hr->syncPermissions($this->permissions);
            $this->new_hr->syncRoles($this->roles);
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }

    /**
     * set_team_hr
     *
     * @param  mixed $hr
     * @return void
     */
    public function set_team_hr(HR $hr)
    {
        if ($this->hr->cannot("set-team-hr")) {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            $this->cancel();
            return null;
        }

        $this->new_hr = $hr;
        $this->key = $this->new_hr->key;
        $this->hoten = $this->new_hr->hoten;

        if (trait_exists(HasNhomTrait::class)) {
            $this->teams = $this->new_hr->thanhvien_of_nhoms->pluck("id")->toArray();
            $this->team_arrays = Nhom::orderBy("order")->select("id", "full_name")->get()->toArray();
        } else {
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Không tồn tại chức năng nhóm"]);
            $this->cancel();
            return null;
        }

        $this->setTeamStatus = true;
        $this->modal_title = "Set team cho nhân sự";
        $this->toastr_message = "Set team cho nhân sự thành công";

        $this->dispatchBrowserEvent('unblockUI');
        $this->dispatchBrowserEvent('dynamic_update');
        $this->dispatchBrowserEvent('show_modal', "#set_team_modal");
    }

    /**
     * set_team_hr_save
     *
     * @return void
     */
    public function set_team_hr_save()
    {
        if ($this->hr->cannot("set-team-hr")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'teams' => 'nullable|array',
            'teams.*' => 'nullable|exists:nhoms,id',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            $this->new_hr->thanhvien_of_nhoms()->sync($this->teams);

            foreach ($this->teams as $ttteam) {
                Nhom_Sync_Job::dispatch(Nhom::find($ttteam), $this->new_hr->key);
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => implode(" - ", $e->errorInfo)]);
            return null;
        } catch (\Exception $e2) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $e2->getMessage()]);
            return null;
        }

        //Đẩy thông tin về trình duyệt
        $this->dispatchBrowserEvent('dt_draw');
        $toastr_message = $this->toastr_message;
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => $toastr_message]);
    }
}
