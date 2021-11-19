<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class UpdateNoteController extends ApiController
{
    /**
     * Create a new note.
     *
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $conditions = [
            'user_id' => $request->user()->id ?? null,
            'id' => $request->id ?? null,
        ];

        $entity = Note::where($conditions)->first();

        if (!$entity) {
            $this->throw('Note not found', [], 404);
        }

        $title = $request->input('title');
        $note = $request->input('note');

        $entity->setAttribute('title', $title);
        $entity->setAttribute('note', $note);
        $entity->setAttribute('user_id', $request->user()->id);

        try {
            $entity->saveOrFail();
        } catch (\Throwable $e) {
            $this->throw('Unable to save note.');
            // FIXME probably best to log something here since we aren't surfacing the real problem to the user
        }

        return (new NoteResource($entity))
            ->toResponse($request);
    }
}
