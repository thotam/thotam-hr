<?php

namespace Thotam\ThotamHr\Http\Livewire;

use Auth;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Redirect;
use Thotam\ThotamIcpc1hnApi\Models\iCPC1HN_Account;
use Thotam\ThotamIcpc1hnApi\Traits\Login\LoginTrait;

class UpdateInfoLivewire extends Component
{
    use LoginTrait;

    /**
    * Các biến sử dụng trong Component
    *
    * @var mixed
    */
    public $hr, $reload;
    public $icpc1hn_taikhoan, $icpc1hn_matkhau;

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
            'mail.*' => 'nullable|email:rfc',
            'mail.noibo' => 'nullable|email:rfc|ends_with:@cpc1hn.com.vn,@cpc1hn.vn',
            'mail.canhan' => 'required|email:rfc',
            'icpc1hn_taikhoan' => $this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien ? "required" : "nullable" . "|string",
            'icpc1hn_matkhau' => $this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien ? "required" : "nullable" . "|string",
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
        'icpc1hn_taikhoan' => 'tài khoản sổ tay',
        'icpc1hn_matkhau' => 'mật khẩu sổ tay',
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
        $this->hr->mails()->whereNull('tag')->delete();
        $this->mail = $this->hr->mails->whereNotNull('tag')->pluck('mail', 'tag')->toArray();
        $this->icpc1hn_taikhoan = optional($this->hr->icpc1hn_account)->account;
        $this->icpc1hn_matkhau = optional($this->hr->icpc1hn_account)->password;
    }

    /**
     * save_info
     *
     * @return void
     */
    public function save_info($reload = null)
    {
        $this->mail = array_filter($this->mail);

        $this->dispatchBrowserEvent('unblockUI');
        $this->validate([
            'mail' => 'nullable|array',
            'mail.*' => 'nullable|email:rfc',
            'mail.noibo' => 'nullable|email:rfc|ends_with:@cpc1hn.com.vn,@cpc1hn.vn',
            'mail.canhan' => 'required|email:rfc',
            'icpc1hn_taikhoan' => $this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien ? "required" : "nullable" . "|string",
            'icpc1hn_matkhau' => $this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien ? "required" : "nullable" . "|string",
        ]);
        $this->dispatchBrowserEvent('blockUI');

        if ($this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien) {
            //Thử check thông tin tài khoản sổ tay
            $response = $this->iCPC1HN_Login($this->icpc1hn_taikhoan, $this->icpc1hn_matkhau);
            if ($response->status() == 200) {
                $json_array = $response->json();
                if ($json_array["ResCode"] != 0) {
                    $this->addError('icpc1hn_taikhoan', $json_array["ResCode"] ." ". $json_array["ResMes"]);
                    $this->dispatchBrowserEvent('unblockUI');
                    return null;
                }
            } else {
                $this->dispatchBrowserEvent('unblockUI');
                $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => 'Unexpected HTTP status: '. $response->status() . ' ' .$response->getReasonPhrase()]);
                return null;
            }

            //Lấy danh sách nhóm
            $gruops = $this->iCPC1HN_Group_Get($response->json()['token']);
            if ($gruops->status() == 200) {
                $json_array = $gruops->json();
                if ($json_array["ResCode"] != 0) {
                    $this->dispatchBrowserEvent('unblockUI');
                    $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $json_array["ResCode"] ." ". $json_array["ResMes"]]);
                    return null;
                }
            } else {
                $this->dispatchBrowserEvent('unblockUI');
                $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => 'Unexpected HTTP status: '. $gruops->status() . ' ' .$gruops->getReasonPhrase()]);
                return null;
            }

            $test_check = false;
            $mkt_check = true;
            foreach ($gruops->json()['Data'] as $gruop) {
                if (collect($gruop)->contains('CNHN-MKT')) {
                    $mkt_check = false;
                }
                if (collect($gruop)->contains('CNHN-Test')) {
                    $test_check = true;
                }
                if ($test_check && !$mkt_check) {
                    break;
                }
            }

            //Thêm nào nhóm MKT
            if ($mkt_check) {
                $addMKT = $this->iCPC1HN_Group_Add($response->json()['Data']['idEmployee'], "CNHN-MKT");
                if ($addMKT->status() == 200) {
                    $json_array = $addMKT->json();
                    if ($json_array["ResCode"] != 0) {
                        $this->dispatchBrowserEvent('unblockUI');
                        $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $json_array["ResCode"] ." ". $json_array["ResMes"]]);
                        return null;
                    }
                } else {
                    $this->dispatchBrowserEvent('unblockUI');
                    $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => 'Unexpected HTTP status: '. $addMKT->status() . ' ' .$addMKT->getReasonPhrase()]);
                    return null;
                }
            }

            //Xóa khỏa nhóm Test
            if ($test_check) {
                $removeTest = $this->iCPC1HN_Group_Delete($response->json()['Data']['idEmployee'], "CNHN-Test");
                if ($removeTest->status() == 200) {
                    $json_array = $removeTest->json();
                    if ($json_array["ResCode"] != 0) {
                        $this->dispatchBrowserEvent('unblockUI');
                        $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => $json_array["ResCode"] ." ". $json_array["ResMes"]]);
                        return null;
                    }
                } else {
                    $this->dispatchBrowserEvent('unblockUI');
                    $this->dispatchBrowserEvent('toastr', ['type' => 'warning', 'title' => "Thất bại", 'message' => 'Unexpected HTTP status: '. $removeTest->status() . ' ' .$removeTest->getReasonPhrase()]);
                    return null;
                }
            }
        }

        //ACtion
        try {
            foreach ($this->mail as $key => $value) {
                $this->hr->updateMail($value, $key);
            }

            if ($this->hr->is_mkt_quanly || $this->hr->is_mkt_thanhvien) {
                $json_array = $response->json();

                if (!!$this->hr->icpc1hn_account) {
                    $icpc1hn_account = $this->hr->icpc1hn_account;

                    $icpc1hn_account->account = $this->icpc1hn_taikhoan;
                    $icpc1hn_account->password = $this->icpc1hn_matkhau;
                    $icpc1hn_account->token = $json_array['token'];
                    $icpc1hn_account->json_array = $json_array;
                    $icpc1hn_account->active = true;

                    $icpc1hn_account->save();
                } else {
                    $icpc1hn_account = new iCPC1HN_Account;
                    $icpc1hn_account->account = $this->icpc1hn_taikhoan;
                    $icpc1hn_account->password = $this->icpc1hn_matkhau;
                    $icpc1hn_account->token = $json_array['token'];
                    $icpc1hn_account->json_array = $json_array;
                    $icpc1hn_account->active = true;

                    $this->hr->icpc1hn_account()->save($icpc1hn_account);
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
        $this->dispatchBrowserEvent('unblockUI');
        $this->cancel();
        $this->dispatchBrowserEvent('toastr', ['type' => 'success', 'title' => "Thành công", 'message' => "Lưu thông tin thành công"]);

        if (!!$reload) {
            return Redirect::to(url()->previous());
        }
    }
}
