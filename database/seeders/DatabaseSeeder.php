<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FiliereSeeder::class,
            AnneeAcademiqueSeeder::class,
            UserSeeder::class,
            MemoireSeeder::class,
        ]);
    }
}