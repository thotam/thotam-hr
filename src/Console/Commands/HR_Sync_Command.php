<?php

namespace Thotam\ThotamHr\Console\Commands;

use Thotam\ThotamHr\Models\HR;
use Illuminate\Console\Command;

class HR_Sync_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thotam-auth:hr-sync';

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

        $hrs = HR::where('sync', false)->limit(100)->get();

        foreach ($hrs as $hr) {


            $hr->update([
                'sync' => true,
            ]);
        }

        return 0;
    }
}
