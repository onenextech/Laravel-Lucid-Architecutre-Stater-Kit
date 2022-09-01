<?php

namespace App\Services\ApplicationService\Features;

use App\Domains\ApplicationService\Jobs\ShowApplicationServiceJob;
use App\Helpers\JsonResponder;
use Lucid\Units\Feature;

class ShowApplicationServiceFeature extends Feature
{
    private string $applicationServiceId;

    public function __construct($applicationServiceId)
    {
        $this->applicationServiceId = $applicationServiceId;
    }

    public function handle()
    {
        $applicationService = $this->run(ShowApplicationServiceJob::class, ['applicationServiceId' => $this->applicationServiceId]);

        return JsonResponder::success('Application Service has been retrieved successfully', $applicationService);
    }
}
