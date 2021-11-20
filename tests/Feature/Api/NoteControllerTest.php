<?php
declare(strict_types=1);
namespace Tests\Feature\Api;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\Facades\App;
use Tests\Feature\ApiTestCase;
use Tests\TestCase;

final class NoteControllerTest extends ApiTestCase
{
    public function testRequiresAuthentication(): void
    {
        $this->assertFalse($this->isAuthenticated());
        $response = $this->json('GET', '/api/notes/1');

        $response->assertStatus(401);
        $this->assertEquals(json_encode(['message' => 'Unauthenticated.']), $response->getContent());
    }

    public function testCanOnlyViewOwnNote(): void
    {
        // some setup for this test. We create 2 notes for the authenticated user, then two notes that don't belong to
        // them. We want to ensure that only their notes are returned here
        $notes = [
            [
                'note' => 'Test Note 1',
                'title' => 'Test Title 1',
                'user_id' => self::FAKE_USER_ID,
            ],
            [
                'note' => 'Test Note 2',
                'title' => 'Test Title 2',
                'user_id' => self::FAKE_USER_ID,
            ],
        ];

        $otherUserNotes = [
            [
                'note' => 'Test Other User Note 1',
                'title' => 'Test Other User Title 1',
                'user_id' => self::FAKE_USER_ID + 1,
            ],
            [
                'note' => 'Test Other User Note 2',
                'title' => 'Test Other User Title 2',
                'user_id' => self::FAKE_USER_ID + 1,
            ],
        ];

        foreach ($notes as $data) {
            $note = new Note($data);
            $note->saveOrFail();
        }

        foreach ($otherUserNotes as $data) {
            $note = new Note($data);
            $note->saveOrFail();
        }

        $notes = Note::all()
            ->pluck('user_id')
            ->toArray();

        // just make sure that we did in fact create 4 notes, 2 for each user ID (42, and 43)
        $expected = [
            42,
            42,
            43,
            43,
        ];

        $this->assertEquals($expected, $notes);

        $note = Note::where(['user_id' => 42])->first();

        $response = $this->withFakeAuthentication()->json('GET', sprintf('/api/notes/%d', $note->id));
        $response->assertStatus(200);
        $data = json_decode($response->getContent(), true);

        $this->assertEquals($note->id, $data['id']);
        $this->assertEquals(self::FAKE_USER_ID, $data['user_id']);
    }
}
