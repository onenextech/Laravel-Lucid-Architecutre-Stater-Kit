<?php

namespace Database\Seeders;

use App\Data\Models\Article;
use App\Data\Models\ArticleCategory;
use App\Data\Models\Comment;
use App\Data\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoBlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user_id = User::first()->id;

        // Create three Article Categories
        $this->command->warn('  Creating three Article Categories');
        $articleCategories = ArticleCategory::factory()->count(3)->create();

        foreach ($articleCategories as $articleCategory) {
            // Create three Articles
            $this->command->warn('  Creating three Articles for Article Category '.$articleCategory->name);
            $articleCategory->articles()->saveMany(Article::factory()->count(3)->create([
                'article_category_id' => $articleCategory->id,
            ]));
        }

        $images = [
            (new File)->fromUrl('https://fakeimg.pl/640x480/?text='.fake()->name()),
            (new File)->fromUrl('https://fakeimg.pl/640x480/?text='.fake()->name()),
            (new File)->fromUrl('https://fakeimg.pl/640x480/?text='.fake()->name()),
        ];

        $articles = Article::all();
        foreach ($articles as $article) {
            // Attach images to article
            $imageIndex = rand(0, 2);
            $article->cover_image = $images[rand(0, 2)];
            $article->slider_images = [
                $images[rand(0, 2)],
                $images[rand(0, 2)],
            ];
            $count = rand(1, 3);

            // Add  comments to article
            $article->comments()->saveMany(Comment::factory()->count($count)->create([
                'created_by' => $user_id,
                'updated_by' => $user_id,
                'article_id' => $article->id,
            ]));

            $article->created_by = $user_id;
            $article->updated_by = $user_id;
            $article->save();
        }
    }
}
