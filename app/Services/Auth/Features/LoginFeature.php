<?php

namespace App\Services\Auth\Features;

use App\Domains\Auth\Jobs\LoginJob;
use App\Domains\Auth\Requests\LoginRequest;
use Lucid\Units\Feature;

class LoginFeature extends Feature
{
    public function handle(LoginRequest $request)
    {
        return $this->run(new LoginJob($request->email,$request->password));
    }
}
