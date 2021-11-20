<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class DeleteNoteController extends ApiController
{
    /**
     * Delete a note.
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $conditions = [
            'user_id' => $request->user()->id,
            'id' => $request->id,
        ];

        $note = Note::where($conditions)->first();

        if (!$note) {
            $this->throw('Note not found.', [], 404);
        }

        try {
            $note->deleteOrFail();
        } catch (\Throwable $e) {
            throw $e;
            $this->throw('Unable to delete note.');
            // FIXME probably best to log something here since we aren't surfacing the real problem to the user
        }

        return $this->newResourceDeletedResponse();
    }
}
