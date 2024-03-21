<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory(1)->create();

        //Descomente las siguientes lienas para crear los registros en la base de datos

        // \App\Models\Challenges::factory(10)->create();
        // \App\Models\Companies::factory(10)->create();
        // \App\Models\Programs::factory(10)->create();
        // \App\Models\ProgramsParticipants::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
