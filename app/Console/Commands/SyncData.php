<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SyncService;

class SyncData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchroniser les données avec le serveur';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Tentative de synchronisation...');

        if ($syncService->syncAll()) {
            $this->info('Synchronisation réussie !');
        } else {
            $this->warn('Impossible de se connecter au serveur. Mode hors ligne.');
        }
    }
}
