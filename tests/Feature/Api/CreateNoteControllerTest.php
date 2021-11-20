<?php
declare(strict_types=1);
namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\Feature\ApiTestCase;
use Tests\TestCase;

final class CreateNoteControllerTest extends ApiTestCase
{
    public function testRequiresAuthentication(): void
    {
        $this->assertFalse($this->isAuthenticated());
        $response = $this->json('POST', '/api/notes', ['note' => 'asdfsadfasdf', 'title' => 'asdfasdfasdf']);

        $response->assertStatus(401);
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response->getContent());
    }

    public function testCanCreateNote(): void
    {
        $data = [
            'note' => 'Test Note',
            'title' => 'Test Title',
        ];

        $response = $this->withFakeAuthentication()->json('POST', '/api/notes', $data);

        $response->assertStatus(201);
        $note = Note::where(['user_id' => self::FAKE_USER_ID])->first();

        $this->assertEquals($data['note'], $note->note);
        $this->assertEquals($data['title'], $note->title);
        $this->assertEquals(self::FAKE_USER_ID, $note->user_id);
    }

    public function testPassingAUserIdHasNoEffect(): void
    {
        $data = [
            'note' => 'Test Note',
            'title' => 'Test Title',
            'user_id' => 999,
        ];

        $response = $this->withFakeAuthentication()->json('POST', '/api/notes', $data);

        $response->assertStatus(201);
        $notes = Note::all()->toArray();
        $this->assertCount(1, $notes);
        $note = $notes[0] ?? null;
        $this->assertNotNull($note);

        $this->assertEquals($data['note'], $note['note']);
        $this->assertEquals($data['title'], $note['title']);
        $this->assertEquals(self::FAKE_USER_ID, $note['user_id']); // make sure it's not 999
    }
}
