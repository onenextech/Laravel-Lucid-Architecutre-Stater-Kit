<?php

namespace App\Helpers;

use Illuminate\Support\Collection;

class Enum
{
    private $enum;

    public function __construct($enum)
    {
        $this->enum = $enum;
    }

    public static function make($enum)
    {
        return new Enum($enum[0]);
    }

    public function names()
    {
        return array_column($this->enum::cases(), 'name');
    }

    public function values()
    {
        return array_column($this->enum::cases(), 'value');
    }

    public function toArray(): array
    {
        return array_combine($this->names(), $this->values());
    }

    public function collection(): Collection
    {
        return collect($this->toArray());
    }
}
