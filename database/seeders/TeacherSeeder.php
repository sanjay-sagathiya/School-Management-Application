<?php

namespace Database\Seeders;

use Database\Factories\TeacherFactory;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run()
    {
        TeacherFactory::new()->count(5)->create();
    }
}
