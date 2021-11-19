<?php

use App\Http\Controllers\Api\CreateNoteController;
use App\Http\Controllers\Api\DeleteNoteController;
use App\Http\Controllers\Api\UpdateNoteController;
use App\Http\Controllers\Api\ViewNoteController;
use App\Http\Resources\NoteCollection;
use App\Http\Resources\NoteResource;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/notes', function (Request $request) {
        $notes = Note::all()
            ->where('user_id', $request->user()->id);

        return new NoteCollection($notes);
    });

    Route::get('notes/{id}', [ViewNoteController::class, 'handle']);
    Route::post('notes', [CreateNoteController::class, 'handle']);
    Route::patch('notes/{id}', [UpdateNoteController::class, 'handle']);
    Route::delete('notes/{id}', [DeleteNoteController::class, 'handle']);
});
