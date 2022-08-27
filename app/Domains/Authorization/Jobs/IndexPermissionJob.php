<?php

namespace App\Domains\Authorization\Jobs;

use Lucid\Units\Job;
use Spatie\Permission\Models\Permission;

class IndexPermissionJob extends Job
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
     * @return \Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\Spatie\Permission\Models\_IH_Permission_C|Permission[]
     */
    public function handle()
    {
        return Permission::all();
    }
}
