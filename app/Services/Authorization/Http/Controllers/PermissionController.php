<?php

namespace App\Services\Authorization\Http\Controllers;

use App\Services\Authorization\Features\IndexPermissionFeature;
use Lucid\Units\Controller;

class PermissionController extends Controller
{
    public function index(){
        return $this->serve(IndexPermissionFeature::class);
    }
}
