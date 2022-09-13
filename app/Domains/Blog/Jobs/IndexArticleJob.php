<?php

namespace App\Domains\Blog\Jobs;

use App\Data\Models\Article;
use Lucid\Units\Job;

class IndexArticleJob extends Job
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
     * @return Article[]|\Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Data\Models\_IH_Article_C
     */
    public function handle()
    {
        return Article::all()->sortByDesc('created_at');
    }
}
