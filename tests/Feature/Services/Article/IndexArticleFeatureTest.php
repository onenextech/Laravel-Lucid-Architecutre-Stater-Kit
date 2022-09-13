<?php

namespace Tests\Feature\Services\Article;

use Tests\TestCase;

class IndexArticleFeatureTest extends TestCase
{
    public function test_index_article_feature()
    {
        $response = $this->get('/api/articles');
        $response->assertStatus(200);
    }
}
