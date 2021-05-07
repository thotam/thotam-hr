<?php

namespace Thotam\ThotamHr\Console\Commands;

use Thotam\ThotamHr\Models\HR;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class HR_Sync_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thotam-hr:hr-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $hrs = HR::where('sync', false)->orWhereNull('sync')->limit(100)->get();

        foreach ($hrs as $hr) {

            DB::connection('member')->table('user_infos')->updateOrInsert(
                [
                    'mnv' => $hr->key,
                ],
                [
                    'full_name' => $hr->hoten,
                    'name' => $hr->ten,
                    'birthday' => $hr->ngaysinh,
                    'ngay_vao_lam' => $hr->ngaythuviec,
                    'active' => $hr->active,
                ]
            );

            $hr->update([
                'sync' => true,
            ]);
        }

        return 0;
    }
}
