<?php

namespace App\Services\Article\Features;

use App\Domains\Blog\Jobs\ShowArticleCategoryJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class ShowArticleCategoryFeature extends Feature
{
    public function handle(Request $request)
    {
        $articleCategory = $this->run(ShowArticleCategoryJob::class, ['articleCategoryId' => $request->articleCategoryId]);

        return JsonResponder::success('Article Category has been successfully retrieved', $articleCategory);
    }
}
