<?php

namespace Tests\Feature\Services\Comment;

use App\Data\Models\Article;
use Tests\TestCase;

class CreateCommentFeatureTest extends TestCase
{
    public function test_create_comment_feature()
    {
        $articleId = Article::first()->id;
        $response = $this->post('/api/comments', [
            'name' => 'John Doe',
            'email' => fake()->email,
            'content' => fake()->paragraph,
            'article_id' => $articleId,
        ]);
        $response->assertStatus(200);
    }
}
