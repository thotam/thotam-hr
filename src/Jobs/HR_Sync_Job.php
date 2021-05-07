<?php

namespace Thotam\ThotamHr\Jobs;

use Illuminate\Bus\Queueable;
use Thotam\ThotamHr\Models\HR;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class HR_Sync_Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $hr;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(HR $hr)
    {
        $this->hr = $hr;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::connection('member')->table('user_infos')->updateOrInsert(
            [
                'mnv' => $this->hr->key,
            ],
            [
                'full_name' => $this->hr->hoten,
                'name' => $this->hr->ten,
                'birthday' => $this->hr->ngaysinh,
                'ngay_vao_lam' => $this->hr->ngaythuviec,
                'active' => $this->hr->active,
            ]
        );

        $this->hr->update([
            'sync' => true,
        ]);
    }
}
