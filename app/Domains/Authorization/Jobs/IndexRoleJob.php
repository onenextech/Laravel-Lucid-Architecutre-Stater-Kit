<?php

namespace App\Domains\Authorization\Jobs;

use Lucid\Units\Job;
use Spatie\Permission\Models\Role;

class IndexRoleJob extends Job
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
     * @return \Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\Spatie\Permission\Models\_IH_Role_C|Role[]
     */
    public function handle()
    {
        return Role::all();
    }
}
