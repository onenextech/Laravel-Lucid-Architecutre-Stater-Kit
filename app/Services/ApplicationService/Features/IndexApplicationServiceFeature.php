<?php

namespace App\Services\ApplicationService\Features;

use App\Domains\ApplicationService\Jobs\IndexApplicationServiceJob;
use App\Helpers\JsonResponder;
use Lucid\Units\Feature;

class IndexApplicationServiceFeature extends Feature
{
    public function handle()
    {
        $applicationServices = $this->run(IndexApplicationServiceJob::class);

        return JsonResponder::success('Application Services have been successfully retrieved', $applicationServices);
    }
}
