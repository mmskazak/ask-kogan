<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VotingTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_vote_on_question()
    {
         $user = User::factory()->create();
         $question  = Question::factory()->create([
            'user_id' => $user->id
         ]);

        $this->assertDatabaseHas('questions', [
            'title' => $question->title,
            'text' =>  $question->text,
            'user_id' => $user->id
        ]);

         $resultBool = $user->vote($question);

         $this->assertTrue($resultBool);

         $this->assertDatabaseHas('voting', [
            'user_id' => $user->id,
            'question_id' =>  $question->id,
        ]);
    }


    public function test_unvote_on_question() {
        $user = User::factory()->create();
        $question  = Question::factory()->create([
            'user_id' => $user->id
        ]);

        $user->vote($question);
        $resultBool = $user->unvote($question);
        $this->assertTrue($resultBool);

        $this->assertDatabaseMissing('voting', [
            'user_id' => $user->id,
            'question_id' =>  $question->id,
        ]);
    }
}
