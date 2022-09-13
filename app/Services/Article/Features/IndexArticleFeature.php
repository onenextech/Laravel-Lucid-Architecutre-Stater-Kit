<?php

namespace App\Services\Article\Features;

use App\Domains\Blog\Jobs\IndexArticleJob;
use App\Helpers\JsonResponder;
use Illuminate\Http\Request;
use Lucid\Units\Feature;

class IndexArticleFeature extends Feature
{
    public function handle(Request $request)
    {
        $articles = $this->run(IndexArticleJob::class);

        return JsonResponder::success('Articles have been successfully retrieved', $articles);
    }
}
