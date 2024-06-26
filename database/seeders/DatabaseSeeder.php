<?php

namespace Database\Seeders;

use App\Models\EventRegistration;
use Database\Factories\EventRegisterFactory;
use Illuminate\Database\Seeder;
use App\Models\User; // Import the User model

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create 10 user records
        User::factory()->count(500)->create();
//        EventRegistration::factory()->count(20)->create();
    }
}
