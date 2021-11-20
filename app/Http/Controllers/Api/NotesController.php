<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class NotesController extends ApiController
{
    /**
     * Retrieve all notes.
     *
     * FIXME: pagination would be nice
     * @return Response
     */
    public function handle(Request $request): Response
    {
        $conditions = [
            'user_id' => $request->user()->id,
        ];

        $notes = Note::where($conditions)->get();

        return (new NoteCollection($notes))
            ->toResponse($request);
    }
}
