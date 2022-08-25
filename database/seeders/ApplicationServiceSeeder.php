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

        collect(config('core.lucid_application_providers'))
            ->pipe(fn ($providers) => ApplicationService::insert($providers->toArray()));
    }
}
