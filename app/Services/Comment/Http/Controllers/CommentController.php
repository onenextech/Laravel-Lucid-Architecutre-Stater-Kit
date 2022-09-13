<?php

namespace App\Services\Comment\Http\Controllers;

use App\Services\Comment\Features\CreateCommentFeature;
use Lucid\Units\Controller;

class CommentController extends Controller
{
    /**
     * Create Comment
     *
     * @group Comment
     *
     * @urlParam article_id required The id of the Article.
     * @bodyParam name string optional The name of the Commenter.
     * @bodyParam email string optional The email of the Commenter.
     * @bodyParam content string required The body of the Comment.
     */
    public function create()
    {
        return $this->serve(CreateCommentFeature::class);
    }
}
