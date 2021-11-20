<?php
declare(strict_types=1);
namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\Feature\ApiTestCase;
use Tests\TestCase;

final class UpdateNoteControllerTest extends ApiTestCase
{
    public function testRequiresAuthentication(): void
    {
        $this->assertFalse($this->isAuthenticated());
        $response = $this->json('PATCH', '/api/notes/1', ['something' => 'doesnt matter']);

        $response->assertStatus(401);
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response->getContent());
    }

    public function testCanUpdateOwnNote(): void
    {
        $data = [
            'note' => 'Test Note 1',
            'title' => 'Test Title 1',
            'user_id' => self::FAKE_USER_ID,
        ];

        $note = new Note($data);
        $note->saveOrFail();

        $this->assertCount(1, Note::all()->toArray());

        $note = Note::where(['user_id' => 42])->first();

        $response = $this->withFakeAuthentication()->json('GET', sprintf('/api/notes/%d', $note->id));
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals($note->id, $data['id']);
        $this->assertEquals(self::FAKE_USER_ID, $data['user_id']);
    }

    public function testCannotUpdateSomeoneElsesNote(): void
    {
        $otherUserId = self::FAKE_USER_ID + 1;

        $data = [
            'note' => 'Test Note 1',
            'title' => 'Test Title 1',
            'user_id' => $otherUserId,
        ];

        $note = new Note($data);
        $note->saveOrFail();

        $this->assertCount(1, Note::all()->toArray());

        $note = Note::where(['user_id' => $otherUserId])->first();

        $response = $this->withFakeAuthentication()->json('GET', sprintf('/api/notes/%d', $note->id));
        $response->assertStatus(404); // it gives a 404 so as not to indicate that it might exist

        $expected = [
            'message' => 'Note not found.',
            'errors' => [],
        ];

        $this->assertEquals(json_encode($expected), $response->getContent());
    }

    /**
     * @group curr2
     */
    public function testPassingAUserIdHasNoEffect(): void
    {
        // first we create our note to edit later
        $data = [
            'note' => 'Test Note 1',
            'title' => 'Test Title 1',
            'user_id' => self::FAKE_USER_ID,
        ];

        $note = new Note($data);
        $note->saveOrFail();

        $this->assertCount(1, Note::all()->toArray());

        $note = Note::where(['user_id' => 42])->first();

        // attempt to change the user_id to someone else
        $data = [
            'user_id' => 999,
        ];

        $response = $this->withFakeAuthentication()->json('PATCH', sprintf('/api/notes/%d', $note->id), $data);

        // this should just be a no-op
        $response->assertStatus(200);
        $notes = Note::all()->toArray();
        $this->assertCount(1, $notes);

        $originalNote = $note;
        $note = $notes[0] ?? null;
        $this->assertNotNull($note);

        $this->assertEquals($originalNote->note, $note['note']);
        $this->assertEquals($originalNote->title, $note['title']);
        $this->assertEquals($originalNote->user_id, $note['user_id']); // make sure it's not 999
    }
}
