<?php

namespace App\Services\ApplicationService\Http\Controllers;

use App\Models\User;
use App\Services\ApplicationService\Features\IndexApplicationServiceFeature;
use App\Services\ApplicationService\Features\ShowApplicationServiceFeature;
use App\Services\ApplicationService\Features\UpdateApplicationServiceFeature;
use Lucid\Units\Controller;

class ApplicationServiceController extends Controller
{
    public function index() {
        return $this->serve(IndexApplicationServiceFeature::class);
    }

    public function show($applicationServiceId) {
        return $this->serve(ShowApplicationServiceFeature::class, ['applicationServiceId' => $applicationServiceId]);
    }

    public function update($applicationServiceId) {
        return $this->serve(UpdateApplicationServiceFeature::class, ['applicationServiceId' => $applicationServiceId]);
    }
}
