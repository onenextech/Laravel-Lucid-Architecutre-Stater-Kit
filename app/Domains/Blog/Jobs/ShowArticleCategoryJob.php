<?php

namespace App\Domains\Blog\Jobs;

use App\Data\Models\ArticleCategory;
use Lucid\Units\Job;

class ShowArticleCategoryJob extends Job
{
    private int $articleCategoryId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($articleCategoryId)
    {
        $this->articleCategoryId = $articleCategoryId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return ArticleCategory::findOrFail($this->articleCategoryId);
    }
}
