<?php

namespace Thotam\ThotamHr\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Thotam\ThotamHr\Models\HR;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Thotam\ThotamUpharma\Traits\JobTrait;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HR_Dropbox_Sync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    use JobTrait;

    public $UserName, $Password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->UserName = config('thotam-upharma.API.User.UserName');
        $this->Password = config('thotam-upharma.API.User.Password');
        $__getAccount = $this->__getAccount();
        $this->Token = $__getAccount['Token'];
        $this->uPharmaID = $__getAccount['UserInfo']['uPharmaID'];

        $mnvs =  HR::where('dropbox', true)->pluck('key')->toArray();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(config('thotam-upharma.API.Employee.GetEmployeeInfoLst'), [
            "uPharmaID" => $this->uPharmaID,
            "Token" => $this->Token,
        ]);

        if ($response->status() == 200) {
            $json_array = $response->json();
            if ($json_array["RespCode"] == 0) {
                $datas = collect($json_array["EmployeeLst"])->whereNotIn('uPharmaIDCode', $mnvs)->whereNotNull('uPharmaIDCode')->sortBy('uPharmaIDCode');

                foreach ($datas as $data) {
                    if (!!$data["uPharmaIDCode"] && !!$data["EmployeeName"]) {
                        $import_hoten = mb_convert_case(trim($data["EmployeeName"]), MB_CASE_TITLE, "UTF-8");
                        $import_names = explode(' ', $import_hoten);
                        $import_ten = array_pop($import_names);

                        HR::updateOrCreate([
                            'key' => $data["uPharmaIDCode"],
                        ], [
                            'hoten' => $import_hoten,
                            'ten' => $import_ten,
                            'ngaysinh' => !!$data['BirthDay'] ? (is_numeric($data['BirthDay']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['BirthDay'])->format('d-m-Y') : Carbon::parse(str_replace("/", "-", $data['BirthDay']))->format('d-m-Y')) : null,
                            'ngaythuviec' => !!$data['TranningTimeStart'] ? (is_numeric($data['TranningTimeStart']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['TranningTimeStart'])->format('d-m-Y') : Carbon::parse(str_replace("/", "-", $data['TranningTimeStart']))->format('d-m-Y')) : null,
                            'active' => true,
                            'dropbox' => true,
                            'sync' => false,
                            'phone' => !!$data["Phone"] ? $data["Phone"] : null,
                        ]);
                    } else {
                        break;
                    }
                }
            } else {
                throw new \Exception(get_class($this) . ': __fetchData - ' .  $json_array["RespCode"] . " " . $json_array["RespText"]);
            }
        } else {
            throw new \Exception(get_class($this) . ': Unexpected HTTP status: __fetchData - ' .  $response->status() . ' - ' . $response->getReasonPhrase());
        }
    }
}
