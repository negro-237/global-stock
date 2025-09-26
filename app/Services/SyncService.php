<?php

// app/Services/SyncService.php
namespace App\Services;

use App\Models\{Account, User};
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncService
{
    protected $apiUrl;
    protected $apiToken;

    public function __construct()
    {
        $this->apiUrl = config('app.sync_url');
        $this->apiToken = config('app.sync_token');
    }

    public function isOnline()
    {
        try {
            return Http::timeout(5)->get($this->apiUrl . '/ping')->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function syncAccount()
    {
        $unsyncedAccounts = Account::unsynced()->get();

        foreach ($unsyncedAccounts as $account) {
            try {
                $response = Http::withToken($this->apiToken)
                    ->post($this->apiUrl . '/api/account/sync', $account->toArray());

                if ($response->successful()) {
                    $account->markAsSynced();
                }
            } catch (\Exception $e) {
                Log::error('Sync account failed: ' . $e->getMessage());
            }
        }
    }

    public function syncUsers()
    {
        $unsyncedUsers = User::unsynced()->get();

        foreach ($unsyncedUsers as $user) {
            try {
                $response = Http::withToken($this->apiToken)
                    ->post($this->apiUrl . '/api/users/sync', $movement->toArray());

                if ($response->successful()) {
                    $user->markAsSynced();
                }
            } catch (\Exception $e) {
                Log::error('Sync users failed: ' . $e->getMessage());
            }
        }
    }

    public function syncAll()
    {
        if ($this->isOnline()) {
            $this->syncAccount();
            $this->syncUsers();
            return true;
        }
        return false;
    }
}
