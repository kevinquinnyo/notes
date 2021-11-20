<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ViewNoteController extends ApiController
{
    /**
     * View a note.
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

        return (new NoteResource($note))
            ->toResponse($request);
    }
}
