<?php

namespace App\Services\ApplicationService\Http\Controllers;

use App\Models\User;
use App\Services\ApplicationService\Features\IndexApplicationServiceFeature;
use App\Services\ApplicationService\Features\ShowApplicationServiceFeature;
use App\Services\ApplicationService\Features\UpdateApplicationServiceFeature;
use Lucid\Units\Controller;

class ApplicationServiceController extends Controller
{
    /**
     * Get Application Services
     * @group ApplicationService
     * 
     */
    public function index() {
        return $this->serve(IndexApplicationServiceFeature::class);
    }

    /**
     * Show Application Service
     * @group ApplicationService
     * 
     * @urlParam id required The id of the Application Service.
     */
    public function show($applicationServiceId) {
        return $this->serve(ShowApplicationServiceFeature::class, ['applicationServiceId' => $applicationServiceId]);
    }

    /**
     * Update Application Service
     * @group ApplicationService
     * 
     * @urlParam id string required The id of the Application Service.
     * @bodyParam active boolean optional The active status of the Application Service.
     * @bodyParam description string optional The description of the Application Service.
     */
    public function update($applicationServiceId) {
        return $this->serve(UpdateApplicationServiceFeature::class, ['applicationServiceId' => $applicationServiceId]);
    }
}
