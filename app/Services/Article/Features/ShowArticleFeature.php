<?php

namespace App\Services\Article\Features;

use App\Domains\Blog\Jobs\ShowArticleJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class ShowArticleFeature extends Feature
{
    public function handle(Request $request)
    {
        $article = $this->run(ShowArticleJob::class, ['articleId' => $request->articleId]);

        return JsonResponder::success('Article has been successfully retrieved', $article);
    }
}
