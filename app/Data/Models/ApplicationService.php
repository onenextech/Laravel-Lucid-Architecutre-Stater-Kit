<?php

namespace App\Data\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationService extends Model
{
    use HasFactory;

    public $fillable = ['description', 'active'];
}
