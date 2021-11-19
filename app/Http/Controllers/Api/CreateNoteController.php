<?php
declare(strict_types=1);
namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class CreateNoteController extends ApiController
{
    /**
     * Create a new note.
     *
     * @return Response
     */
    public function create(Request $request): Response
    {
        $title = $request->input('title');
        $note = $request->input('note');

        if (!$title) {
            $this->throw('Missing required params', ['title' => 'Required']);
        }

        if (!$note) {
            $this->throw('Missing required params', ['note' => 'Required']);
        }

        $entity = new Note();
        $entity->setAttribute('title', $title);
        $entity->setAttribute('note', $note);
        $entity->setAttribute('user_id', $request->user()->id);

        try {
            $entity->saveOrFail();
        } catch (\Throwable $e) {
            $this->throw('Unable to save note.');
            // FIXME probably best to log something here since we aren't surfacing the real problem to the user
        }

        return $this->newResourceCreatedResponse();
    }
}
