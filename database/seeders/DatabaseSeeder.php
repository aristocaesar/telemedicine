<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{


    

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PattientSeeder::class);
        $this->call([RegistrationOfficersSeeder::class , PolyclinicSeeder::class , DoctorSeeder::class]);
            
        $this->call([
            AdminSeeder::class , MedicinesSeeder::class , MedicalRecordsSeeder::class , RecordSeeder::class 
        ]);

        // \App\Models\User::factory(10)->create();

      
        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
