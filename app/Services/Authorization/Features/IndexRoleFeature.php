<?php

namespace App\Services\Authorization\Features;

use App\Domains\Authorization\Jobs\IndexRoleJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class IndexRoleFeature extends Feature
{
    public function handle(Request $request)
    {
        $roles = $this->run(IndexRoleJob::class);
        return JsonResponder::success('Roles has been retrieved successfully', $roles);
    }
}
