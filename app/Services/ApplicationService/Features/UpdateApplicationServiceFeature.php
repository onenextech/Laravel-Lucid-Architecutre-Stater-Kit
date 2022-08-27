<?php

namespace App\Services\ApplicationService\Features;

use App\Domains\ApplicationService\Jobs\RenewApplicationServiceInCacheJob;
use App\Domains\ApplicationService\Jobs\ShowApplicationServiceJob;
use App\Domains\ApplicationService\Jobs\UpdateApplicationServiceJob;
use App\Domains\ApplicationService\Requests\UpdateApplicationServiceRequest;
use App\Helpers\JsonResponder;
use Lucid\Units\Feature;

class UpdateApplicationServiceFeature extends Feature
{
    private string $applicationServiceId;

    public function __construct($applicationServiceId)
    {
        $this->applicationServiceId = $applicationServiceId;
    }

    public function handle(UpdateApplicationServiceRequest $request)
    {
        $this->run(UpdateApplicationServiceJob::class,
            ['applicationServiceId' => $this->applicationServiceId, 'payload' => $request->all()]);

        $this->run(RenewApplicationServiceInCacheJob::class);

        $applicationService = $this->run(ShowApplicationServiceJob::class, ['applicationServiceId' => $this->applicationServiceId]);
        return JsonResponder::success('Application Service has been updated successfully', $applicationService);
    }
}
