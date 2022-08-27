<?php

namespace App\Domains\ApplicationService\Jobs;

use App\Data\Models\ApplicationService;
use Illuminate\Support\Facades\Cache;
use Lucid\Units\Job;

class RenewApplicationServiceInCacheJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Cache::put('application_services', ApplicationService::all()->sortByDesc('active'));
    }
}
