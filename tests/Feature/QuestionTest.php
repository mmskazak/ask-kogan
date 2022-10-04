<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use DatabaseTransactions;

    private User $user;

    public function setUp():void {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_create_question_from_user()
    {
        $title = 'title question';
        $text = 'text question';

        $question = $this->user->createQuestion($title,$text);

        $this->assertEquals($title, $question->title);
        $this->assertEquals($text, $question->text);
        $this->assertDatabaseHas('questions', [
            'title' => $title,
            'text' => $text,
        ]);
    }

    public function test_update_question_from_user() {

        $title = 'title question';
        $text = 'text question';

        $question = $this->user->createQuestion($title,$text);

        $newTitle = 'new title question';
        $newText = 'new text question';

        $question = $this->user->updateQuestion($question,$newTitle,$newText);

        $this->assertEquals($newTitle, $question->title);
        $this->assertEquals($newText, $question->text);
        $this->assertDatabaseHas('questions', [
            'title' => $newTitle,
            'text' => $newText,
        ]);

    }
}
