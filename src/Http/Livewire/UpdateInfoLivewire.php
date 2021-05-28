<?php

namespace Thotam\ThotamHr\Http\Livewire;

use Auth;
use Carbon\Carbon;
use Livewire\Component;

class UpdateInfoLivewire extends Component
{

    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $hr;

    public $mail = [];

    /**
     * Các biển sự kiện
     *
     * @var array
     */
    protected $listeners = ['dynamic_update_method'];

    /**
     * Validation rules
     *
     * @var array
     */
    protected function rules() {
        return [
            'mail' => 'nullable|array',
            'mail.*' => 'nullable|email:rfc,dns',
            'mail.canhan' => 'required',
            'mail.noibo' => 'ends_with:@cpc1hn.com.vn,@cpc1hn.vn',
        ];
    }

    /**
     * Custom attributes
     *
     * @var array
     */
    protected $validationAttributes = [
        'mail' => 'email',
        'mail.*' => 'email',
        'mail.noibo' => 'email nội bộ',
        'mail.canhan' => 'email cá nhân',
    ];

    /**
     * cancel
     *
     * @return void
     */
    public function cancel()
    {
        $this->reset();
        $this->resetValidation();
        $this->mount();
    }

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

    public function render()
    {
        return view('thotam-hr::livewire.update-info.update-info-livewire');
    }

    /**
     * mount data
     *
     * @return void
     */
    public function mount()
    {
        $this->hr = Auth::user()->hr;
        $this->mail = $this->hr->mails->pluck('mail', 'tag')->toArray();
    }

    /**
     * save_info
     *
     * @return void
     */
    public function save_info()
    {
        $this->mail = array_filter($this->mail);

        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'mail' => 'nullable|array',
            'mail.*' => 'nullable|email:rfc,dns',
            'mail.canhan' => 'required',
            'mail.noibo' => 'ends_with:@cpc1hn.com.vn,@cpc1hn.vn',
        ]);
        $this->dispatchBrowserEvent('blockUI');

        try {
            foreach ($this->mail as $key => $value) {
                $this->hr->updateMail($value, $key);
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
        $this->dispatchBrowserEvent('unblockUI');
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => "Lưu thông tin thành công"]);
    }
}
