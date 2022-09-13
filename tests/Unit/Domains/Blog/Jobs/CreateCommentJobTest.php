<?php

namespace Tests\Unit\Domains\Blog\Jobs;

use App\Data\Models\Article;
use App\Data\Models\Comment;
use App\Domains\Blog\Jobs\CreateCommentJob;
use App\Domains\Blog\Requests\CommentRequest;
use Tests\TestCase;

class CreateCommentJobTest extends TestCase
{
    public function test_create_comment_job()
    {
        $articleId = Article::first()->id;
        $request = new CommentRequest([
            'article_id' => $articleId,
            'name' => 'John Doe',
            'email' => fake()->email,
            'content' => fake()->paragraph,
        ]);

        $comment = (new CreateCommentJob($request))->handle();
        $this->assertInstanceOf(Comment::class, $comment);
    }
}
