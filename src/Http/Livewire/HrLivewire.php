<?php

namespace Thotam\ThotamHr\Http\Livewire;

use Auth;
use Livewire\Component;
use Thotam\ThotamHr\Models\HR;

class HrLivewire extends Component
{
    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $key, $hoten, $ten, $ngaysinh, $ngaythuviec, $active, $old_key, $old_hr, $new_hr;
    public $modal_title, $toastr_message;
    public $hr;

    /**
     * @var bool
     */
    public $addStatus = false;
    public $viewStatus = false;
    public $editStatus = false;
    public $setPermissionStatus = false;


    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method', 'add_hr', 'edit_hr', 'set_permission_hr', ];

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
        if ($this->hr->cannot("add-user")) {
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
        if ($this->hr->cannot("edit-user")) {
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
        $this->active = $this->new_hr->active;

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
        if ($this->hr->cannot("add-user")) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không có quyền thực hiện hành động này"]);
            return null;
        }

        $this->updatedHoten();

        if (($this->hr->key == $this->old_hr->key) && ($this->key != $this->old_key)) {
            $this->dispatchBrowserEvent('unblockUI');
            $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => "Bạn không thể chỉnh sửa mã nhân sự của chính bản thân mình"]);
            return null;
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
                HR::create([
                    "key" => $this->key,
                    "hoten" => $this->hoten,
                    "ten" => $this->ten,
                    "ngaysinh" => $this->ngaysinh,
                    "ngaythuviec" => $this->ngaythuviec,
                ]);
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
