<?php

namespace App\Domains\ApplicationService\Jobs;

use App\Data\Models\ApplicationService;
use Lucid\Units\Job;

class IndexApplicationServiceJob extends Job
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
     * @return ApplicationService[]|\Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Data\Models\_IH_ApplicationService_C
     */
    public function handle()
    {
        return ApplicationService::all()->sortByDesc('active');
    }
}
