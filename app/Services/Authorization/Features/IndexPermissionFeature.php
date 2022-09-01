<?php

namespace App\Services\Authorization\Features;

use App\Domains\Authorization\Jobs\IndexPermissionJob;
use App\Helpers\JsonResponder;
use Lucid\Units\Feature;

class IndexPermissionFeature extends Feature
{
    public function handle()
    {
        $permissions = $this->run(IndexPermissionJob::class);

        return JsonResponder::success('Permissions have been retrieved successfully', $permissions);
    }
}
