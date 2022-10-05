<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
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

        Auth::login($this->user);
        $question = $this->user->updateQuestion($question,$newTitle,$newText);

        $this->assertEquals($newTitle, $question->title);
        $this->assertEquals($newText, $question->text);
        $this->assertDatabaseHas('questions', [
            'title' => $newTitle,
            'text' => $newText,
        ]);

    }

    public function test_update_self_question() {
        $user1 = $this->user;
        $user2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        $title = 'test title question';
        $text = 'test text question';
        $question = $user1->createQuestion($title,$text);

        Auth::login($user2);

        $newTitle = 'new test title question';
        $newText = 'new test text question';

        $this->expectExceptionMessage('Ошибка обновления вопроса');
        $user2->updateQuestion($question, $newTitle, $newText);

        $this->assertDatabaseHas('questions', [
            'user_id' => $user1->id,
            'title' => $title,
            'text' => $text,
        ]);
    }


    public function test_delete_question_from_user() {

         $title = 'title question';
         $text = 'text question';

         $question = $this->user->createQuestion($title,$text);

         Auth::login($this->user);
         $isTrue = $this->user->deleteQuestion($question);

         $this->assertTrue($isTrue);

        $this->assertDatabaseMissing('questions', [
            'user_id' => $question->user_id,
            'title' => $question->title,
            'text' => $question->text,
        ]);
    }


    public function test_delete_self_question() {
        $user1 = $this->user;
        $user2 = User::factory()->create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        $title = 'test title question';
        $text = 'test text question';
        $question = $user1->createQuestion($title,$text);

        Auth::login($user2);

        $this->expectExceptionMessage('Ошибка удаления вопроса');
        $user2->deleteQuestion($question);

        $this->assertDatabaseHas('questions', [
            'user_id' => $user1->id,
            'title' => $title,
            'text' => $text,
        ]);
    }
}
