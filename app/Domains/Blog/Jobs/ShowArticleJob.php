<?php

namespace App\Domains\Blog\Jobs;

use App\Data\Models\Article;
use Lucid\Units\Job;

class ShowArticleJob extends Job
{
    private int $articleId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleId)
    {
        $this->articleId = $articleId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return Article::findOrFail($this->articleId);
    }
}
