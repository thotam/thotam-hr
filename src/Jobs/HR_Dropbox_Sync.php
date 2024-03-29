<?php

namespace Thotam\ThotamHr\Jobs;

use Carbon\Carbon;
use Spatie\Dropbox\Client;
use Illuminate\Bus\Queueable;
use Thotam\ThotamHr\Models\HR;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Thotam\ThotamHr\Imports\BfoDropBoxImport;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Thotam\ThotamHr\Service\AutoRefreshingDropBoxTokenService;

class HR_Dropbox_Sync implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


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

        $mnvs =  HR::where('dropbox', true)->pluck('key')->toArray();
        $tokenProvider = new AutoRefreshingDropBoxTokenService();
        $client = new Client($tokenProvider);
        Storage::writeStream("HR/ImportHr/HR.xlsx", $client->download("id:bydpVjt3rGcAAAAAAABhHQ"));
        $datas = collect(Excel::toArray(new BfoDropBoxImport, "HR/ImportHr/HR.xlsx")[0])->whereNotIn('ma_nv', $mnvs)->whereNotNull('ma_nv')->sortBy('ma_nv');

        foreach ($datas as $data) {
            if (!!$data["ma_nv"] && !!$data["ho_va_ten"]) {
                if ($data['ma_nv'] != '#N/A' && $data['ho_va_ten'] != '#N/A') {
                    $import_hoten = mb_convert_case(trim($data["ho_va_ten"]), MB_CASE_TITLE, "UTF-8");
                    $import_names = explode(' ', $import_hoten);
                    $import_ten = array_pop($import_names);

                    HR::updateOrCreate([
                        'key' => $data["ma_nv"],
                    ], [
                        'hoten' => $import_hoten,
                        'ten' => $import_ten,
                        'ngaysinh' => (!!$data['ngay_thang_nam_sinh'] && $data['ngay_thang_nam_sinh'] != '#N/A') ? (is_numeric($data['ngay_thang_nam_sinh']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['ngay_thang_nam_sinh'])->format('d-m-Y') : Carbon::parse(str_replace("/", "-", $data['ngay_thang_nam_sinh']))->format('d-m-Y')) : null,
                        'ngaythuviec' => (!!$data['ngay_vao'] && $data['ngay_vao'] != '#N/A') ? (is_numeric($data['ngay_vao']) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($data['ngay_vao'])->format('d-m-Y') : Carbon::parse(str_replace("/", "-", $data['ngay_vao']))->format('d-m-Y')) : null,
                        'active' => true,
                        'dropbox' => true,
                        'sync' => false,
                        // 'phone' => !!$data["dien_thoai_lien_he"] ? $data["dien_thoai_lien_he"] : null,
                    ]);
                }
            } else {
                break;
            }
        }
    }
}
