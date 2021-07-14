<?php

namespace Thotam\ThotamHr\Http\Livewire;

use Auth;
use Livewire\Component;
use Thotam\ThotamHr\Models\HR;
use Thotam\ThotamHr\Models\UpdateHr;
use Illuminate\Support\Facades\Redirect;

class UpdateHrLivewire extends Component
{
    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $hoten, $hr_key, $nhom, $ngaysinh, $ngaythuviec;
    public $user, $update_hr;


    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method' ];

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
            'hoten' => 'required|string|max:255',
            'nhom' => 'required|string|max:255',
            'hr_key' => 'nullable|exists:hrs,key',
            'ngaysinh' => 'required|date_format:d-m-Y',
            'ngaythuviec' => 'required|date_format:d-m-Y',
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'hoten' => 'họ tên',
        'nhom' => 'nhóm',
        'ngaysinh' => 'ngày sinh',
        'hr_key' => 'mã nhân sự',
        'ngaythuviec' => 'ngày thử việc',
    ];

    public function updatedHoten()
    {
        $this->hoten = mb_convert_case(trim($this->hoten), MB_CASE_TITLE, "UTF-8");
    }

    /**
     * render
     *
     * @return void
     */
    public function render()
    {
        return view('thotam-hr::livewire.update-hr.update-hr-livewire');
    }

    /**
     * mount
     *
     * @return void
     */
    public function mount()
    {
        $this->user = Auth::user();

        if (!!$this->user->update_hr) {
            $this->update_hr = $this->user->update_hr;
            $this->hoten = $this->update_hr->hoten;
            $this->hr_key = optional($this->update_hr)->hr_key;
            $this->nhom = optional($this->update_hr)->nhom;
            $this->ngaysinh = optional(optional($this->update_hr)->ngaysinh)->format("d-m-Y");
            $this->ngaythuviec = optional(optional($this->update_hr)->ngaythuviec)->format("d-m-Y");
        } else {
            $this->hoten = $this->user->name;
        }
    }

    /**
     * save
     *
     * @return void
     */
    public function save()
    {
        //Xác thực dữ liệu
        $this->validate();

        if (!!$this->user->update_hr) {
            $this->update_hr = $this->user->update_hr;
        } else {
            $this->update_hr = new UpdateHr;
        }

        $this->update_hr->hoten = mb_convert_case(trim($this->hoten), MB_CASE_TITLE, "UTF-8");
        $this->update_hr->hr_key = !!$this->hr_key ? $this->hr_key : NULL;
        $this->update_hr->nhom = $this->nhom;
        $this->update_hr->ngaysinh = $this->ngaysinh;
        $this->update_hr->ngaythuviec = $this->ngaythuviec;

        $this->user->update_hr()->save($this->update_hr);

        $this->mount();

        $HR_Info = HR::find($this->hr_key);

        if (!!$HR_Info && ($HR_Info->hoten == $this->update_hr->hoten) && (($HR_Info->ngaysinh->format("d-m-Y") == $this->update_hr->ngaysinh->format("d-m-Y")) && ($HR_Info->ngaythuviec->format("d-m-Y") == $this->update_hr->ngaythuviec->format("d-m-Y")))) {
            if ($this->user->hr()->associate($HR_Info)->save()) {
                $HR_Info->update(["active", true]);
                return Redirect::to(url()->previous());
            };
        }

    }
}
