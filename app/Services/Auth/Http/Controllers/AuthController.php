<?php

namespace App\Services\Auth\Http\Controllers;

use App\Domains\Auth\Requests\LoginRequest;
use App\Services\Auth\Features\LoginFeature;
use Lucid\Units\Controller;

class AuthController extends Controller
{
    public function login() {
        return $this->serve(LoginFeature::class);
    }
}
