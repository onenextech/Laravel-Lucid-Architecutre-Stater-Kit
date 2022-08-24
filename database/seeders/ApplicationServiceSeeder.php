<?php

namespace Database\Seeders;

use App\Data\Models\ApplicationService;
use Illuminate\Database\Seeder;

class ApplicationServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ApplicationService::truncate();

        collect(config('custom.lucid_application_providers'))
            ->map(fn($provider) => ['provider' => $provider, 'active' => true])
            ->pipe(fn ($providers) => ApplicationService::insert($providers->toArray()));
    }
}
