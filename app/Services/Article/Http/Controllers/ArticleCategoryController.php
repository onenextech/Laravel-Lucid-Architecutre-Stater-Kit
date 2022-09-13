<?php

namespace App\Services\Article\Http\Controllers;

use App\Services\Article\Features\IndexArticleCategoryFeature;
use App\Services\Article\Features\ShowArticleCategoryFeature;
use Lucid\Units\Controller;

class ArticleCategoryController extends Controller
{
    /**
     * Get Article Categories
     *
     * @group ArticleCategory
     */
    public function index()
    {
        return $this->serve(IndexArticleCategoryFeature::class);
    }

    /**
     * Show Article Category
     *
     * @ urlParam id required The id of the Article Category.
     * @group ArticleCategory
     */
    public function show($articleCategoryId)
    {
        return $this->serve(ShowArticleCategoryFeature::class, ['articleCategoryId' => $articleCategoryId]);
    }
}
