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
    public function handle(Request $request): Response
    {
        $title = $request->input('title');
        $note = $request->input('note');

        $data = [
            'title' => $title,
            'note' => $note,
            'user_id' => $request->user()->id,
        ];

        $entity = new Note($data);

        if (!$entity->save()) {
            $this->throw('Unable to save note.', $entity->getErrors(), 400);
        }

        return $this->newResourceCreatedResponse();
    }
}
