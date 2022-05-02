<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Admin\CronRepository;

class CronStatus extends Command
{

    protected $signature = 'cron_status';
    protected $description = 'Save Cron status every minute';

    
    public function __construct(CronRepository $cronRepo)
    {
        Parent::__construct();
        $this->cronRepo = $cronRepo;
    }
    


    public function handle()
    {
        $this->cronRepo->saveCronStatus();
    }



}
