<?php
declare(strict_types=1);
namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\Feature\ApiTestCase;
use Tests\TestCase;

final class DeleteNoteControllerTest extends ApiTestCase
{
    public function testRequiresAuthentication(): void
    {
        $this->assertFalse($this->isAuthenticated());
        $response = $this->json('DELETE', '/api/notes/1');

        $response->assertStatus(401);
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response->getContent());
    }

    /**
     * @group curr
     */
    public function testCanDeleteOwnNote(): void
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

        $response = $this->withFakeAuthentication()->json('DELETE', sprintf('/api/notes/%d', $note->id));
        $response->assertStatus(204);
        $this->assertEmpty($response->getContent());
    }

    /**
     * @group curr
     */
    public function testCannotDeleteSomeoneElsesNote(): void
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

        $response = $this->withFakeAuthentication()->json('DELETE', sprintf('/api/notes/%d', $note->id));
        $response->assertStatus(404); // it gives a 404 so as not to indicate that it might exist

        $expected = [
            'message' => 'Note not found.',
            'errors' => [],
        ];

        $this->assertEquals(json_encode($expected), $response->getContent());
    }
}
