<?php

namespace Tests\Feature\Services\Article;

use App\Data\Models\Article;
use Tests\TestCase;

class ShowArticleFeatureTest extends TestCase
{
    public function test_show_article_feature()
    {
        $id = Article::first()->id;
        $response = $this->get("/api/articles/$id");
        $response->assertStatus(200);
    }
}
