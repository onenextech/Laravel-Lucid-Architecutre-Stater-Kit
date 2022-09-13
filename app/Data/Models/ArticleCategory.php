<?php

namespace App\Data\Models;

use App\Traits\BasicAudit;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

class ArticleCategory extends Model
{
    use SnowflakeID, HasFactory, HasPermissions, SoftDeletes, BasicAudit;

    public $fillable = ['name'];

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
