<?php

namespace App\Services\Authorization\Features;

use App\Domains\Authorization\Jobs\IndexPermissionJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\JsonResponse;
use Lucid\Units\Feature;

class IndexPermissionFeature extends Feature
{
    public function handle(): JsonResponse
    {
        $permissions = $this->run(IndexPermissionJob::class);

        return JsonResponder::success('Permissions have been retrieved successfully', $permissions);
    }
}
