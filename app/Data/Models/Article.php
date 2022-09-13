<?php

namespace App\Data\Models;

use App\Traits\BasicAudit;
use App\Traits\HasAttachable;
use App\Traits\SnowflakeID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasPermissions;

class Article extends Model
{
    use SnowflakeID, HasFactory, HasPermissions, SoftDeletes, BasicAudit, HasAttachable;

    public $fillable = ['title', 'content'];

    protected $attachOne = [
        'cover_image' => File::class,
    ];

    protected $attachMany = [
        'slider_images' => File::class,
    ];

    public function articleCategory()
    {
        return $this->belongsTo(ArticleCategory::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
