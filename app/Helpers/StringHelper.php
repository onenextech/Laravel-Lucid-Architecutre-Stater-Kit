<?php

namespace App\Helpers;

use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class StringHelper
{
    #[ArrayShape(['name' => 'mixed', 'action' => 'mixed', 'resource' => 'mixed'])]
    public static function extractPermissionName($permission, $divider = '-'): array
    {
        return [
            'name' => Str::title(Str::replace($divider, ' ', $permission)),
            'action' => Str::before($permission, $divider),
            'resource' => Str::after($permission, $divider),
        ];
    }
}
