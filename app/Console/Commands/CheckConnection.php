<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'connection:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifier l\'état de la connexion';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    }
}
