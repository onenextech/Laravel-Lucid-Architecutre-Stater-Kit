<?php

namespace Tests\Feature\Services\Article;

use App\Data\Models\ArticleCategory;
use Tests\TestCase;

class ShowArticleCategoryFeatureTest extends TestCase
{
    public function test_show_article_category_feature()
    {
        $id = ArticleCategory::first()->id;
        $response = $this->get("/api/articles/categories/$id");
        $response->assertStatus(200);
    }
}
