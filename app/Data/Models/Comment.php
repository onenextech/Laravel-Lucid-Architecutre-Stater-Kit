<?php

namespace App\Data\Models;

use App\Traits\BasicAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

class Comment extends Model
{
    use HasFactory, HasPermissions, SoftDeletes, BasicAudit;

    public $fillable = ['name', 'email', 'content'];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
