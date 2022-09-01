<?php

namespace App\Services\Authorization\Http\Controllers;

use App\Services\Authorization\Features\IndexRoleFeature;
use Lucid\Units\Controller;

class RoleController extends Controller
{
    /**
     * Index Authorization Roles
     *
     * @group Authorization
     */
    public function index()
    {
        return $this->serve(IndexRoleFeature::class);
    }
}
