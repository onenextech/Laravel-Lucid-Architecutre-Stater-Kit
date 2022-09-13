<?php

namespace App\Services\Article\Features;

use App\Domains\Blog\Jobs\IndexArticleCategoryJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class IndexArticleCategoryFeature extends Feature
{
    public function handle(Request $request)
    {
        $articleCategories = $this->run(IndexArticleCategoryJob::class);

        return JsonResponder::success('Article Categories have been successfully retrieved', $articleCategories);
    }
}
