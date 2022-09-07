<?php

namespace App\Data\Models;

use App\Traits\Attachable as AttachableTrait;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory , AttachableTrait, SnowflakeID;

    protected $fillable = [
        'file_name',
        'title',
        'description',
        'field',
        'attachable_id',
        'attachable_type',
        'is_public',
        'sort_order',
        'data',
    ];

    protected $guarded = [];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    protected $hidden = ['attachable_type', 'attachable_id', 'is_public'];

    public function attachable()
    {
        return $this->morphTo();
    }
}
