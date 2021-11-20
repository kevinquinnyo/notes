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
     * Update a note.
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

        $entity->title = $request->input('title');
        $entity->note = $request->input('note');

        if (!$entity->save()) {
            $this->throw('Unable to update note.', $entity->getErrors(), 400);
        }

        return (new NoteResource($entity))
            ->toResponse($request);
    }
}
