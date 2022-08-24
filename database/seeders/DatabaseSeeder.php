<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Psy\Readline\Hoa\Console;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('custom.lucid_application_providers')) {
            $this->call(ApplicationServiceSeeder::class);
        }
    }
}
