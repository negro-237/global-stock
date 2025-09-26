<?php

namespace Database\Seeders;

use App\Models\{Account, User};
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $account = Account::create();

        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john.doe@gmail.com',
            'account_id' => $account->id,
        ]);
    }
}
