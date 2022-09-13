<?php

namespace Tests\Feature\Services\Article;

use Tests\TestCase;

class IndexArticleCategoryFeatureTest extends TestCase
{
    public function test_index_article_category_feature()
    {
        $response = $this->get('/api/articles/categories');
        $response->assertStatus(200);
    }
}
