<?php

namespace App\Domains\Blog\Jobs;

use App\Data\Models\Comment;
use Lucid\Units\Job;

class CreateCommentJob extends Job
{
    private $comment;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Execute the job.
     *
     * @return Comment|\Illuminate\Database\Eloquent\Model|\LaravelIdea\Helper\App\Data\Models\_IH_Comment_C
     */
    public function handle()
    {
        return Comment::create([
            'article_id' => $this->comment['article_id'],
            'name' => $this->comment['name'],
            'email' => $this->comment['email'],
            'content' => $this->comment['content'],
        ]);
    }
}
