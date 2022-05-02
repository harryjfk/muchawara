<?php

namespace App\Console\Commands;

use App\Models\Spotlight;
use Illuminate\Console\Command;

class DeleteUserSpotlight extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:user:spotlight';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for delete the users from spotlight after 24 hours';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $spotlight = Spotlight::all();
      foreach ($spotlight as $spotlight) {
          $currentDate = new \DateTime();
          $day = $currentDate->diff($spotlight->updated_at)->d;
          if($day >= 1)
             $spotlight->delete();
      }

    }
}
