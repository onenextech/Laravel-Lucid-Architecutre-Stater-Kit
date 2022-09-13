<?php

namespace App\Domains\Blog\Jobs;

use App\Data\Models\ArticleCategory;
use Lucid\Units\Job;

class IndexArticleCategoryJob extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return ArticleCategory[]|Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Data\Models\_IH_ArticleCategory_C
     */
    public function handle()
    {
        return ArticleCategory::all()->sortBy('name');
    }
}
