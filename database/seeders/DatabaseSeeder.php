<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
		// Seed an admin user
		$this->call(AdminSeeder::class);

		// Seed teacher users
		$this->call(TeacherSeeder::class);
    }
}
