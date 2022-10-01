<?php

namespace Thotam\ThotamHr\Console\Commands;

use Illuminate\Console\Command;

class DropBoxHR_Sync_Command extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'thotam-hr:dropbox-hr-sync';

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
        \Thotam\ThotamHr\Jobs\HR_Dropbox_Sync::dispatch();
        return 0;
    }
}
