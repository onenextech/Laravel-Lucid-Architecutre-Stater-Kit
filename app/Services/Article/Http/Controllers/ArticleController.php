<?php

namespace App\Services\Article\Http\Controllers;

use App\Services\Article\Features\IndexArticleFeature;
use App\Services\Article\Features\ShowArticleFeature;
use Lucid\Units\Controller;

class ArticleController extends Controller
{
    /**
     * Get Articles
     *
     * @group Article
     */
    public function index()
    {
        return $this->serve(IndexArticleFeature::class);
    }

    /**
     * Show Article
     *
     * @group Article
     *
     * @urlParam id required The id of the Article.
     */
    public function show($articleId)
    {
        return $this->serve(ShowArticleFeature::class, ['articleId' => $articleId]);
    }
}
