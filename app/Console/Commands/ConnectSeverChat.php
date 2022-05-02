<?php
/**
 * Created by PhpStorm.
 * User: DellK
 * Date: 24/07/2018
 * Time: 11:59
 */

namespace App\Console\Commands;


use App\Components\Plugin;
use App\Http\Controllers\WebsocketServerController;
use App\Models\User;
use Illuminate\Console\Command;

class ConnectSeverChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'server_chat:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command for start chat server every 1 hour';

    private $serverRepo;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->serverRepo = app('App\Repositories\WebsocketServerRepository');
        $this->serverRepo->server_stop();

       if(! $this->serverRepo->server_running()) {
           $status = $this->serverRepo->startServer() ? 'success' : 'error';

           $this->info(sprintf("%s %s", "Server Response ", $status));

           if($status == "error") {

               $user = User::where('username', '=', 'ppyonki.cu@gmail.com')->first();

               $email_array = new \stdClass();
               $email_array->user = $user;
               $email_array->type = 'birthday';

               Plugin::fire('send_email', $email_array);
           }
       } else {
           $this->info("The server is running");
       }

    }
}