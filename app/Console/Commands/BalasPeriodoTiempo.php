<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class BalasPeriodoTiempo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balas:seisHoras';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando otorga 5 balas a los clientes cada 6 horas';

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
     * @return mixed
     */
    public function handle()
    {
        //
        $users = User::where('id','>',0)->get();
        
        foreach($users as $user)
        {
//            $user->credits->balance = $user->credits->balance + 5;
            $user->take_credits = true;
            $user->save();
        }
    }
}
