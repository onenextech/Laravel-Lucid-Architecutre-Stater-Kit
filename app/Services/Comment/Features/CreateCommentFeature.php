<?php

namespace App\Services\Comment\Features;

use App\Domains\Blog\Jobs\CreateCommentJob;
use App\Domains\Blog\Requests\CommentRequest;
use App\Helpers\JsonResponder;
use Lucid\Units\Feature;

class CreateCommentFeature extends Feature
{
    public function handle(CommentRequest $request)
    {
        $comment = $this->run(CreateCommentJob::class, ['comment' => $request]);

        return JsonResponder::success('Comment has been successfully created', $comment);
    }
}
