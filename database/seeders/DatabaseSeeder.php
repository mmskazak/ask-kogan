<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $count = 0;
        User::factory(100)->create()->each(function($user) use (&$count) {
            $random = rand(0, 1);
            if($random && $count < 10) {

                $count++;
                $question = Question::factory()->make();
                $user->questions()->create([
                    'title' => $question->title,
                    'text' => $question->text,
                    'slug' => $question->slug,
                    'moderation' => 'active',
                ]);
            }
        });


        User::all()->random(50)->each(function ($user) {
            $question = Question::all()->random(1);
            $user->votings()->attach($question);
        });
    }
}
