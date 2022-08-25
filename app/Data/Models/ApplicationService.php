<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApplicationService extends Model
{
    use HasFactory;

    public $fillable = ['description', 'active'];
}
