<?php

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

//Route::middleware('auth:sanctum')
//    ->get('/notes', function (Request $request) {
//        $notes = Note::all()
//            ->where(['user_id', $request->user()->id]);
//
//        return new NoteCollection($notes);
//    })
//    ->get('notes/{id}', function (Request $request) {
//        $note = Note::where(['user_id', $request->user()->id]);
//
//        return NoteResource($note);
//    });

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/notes', function (Request $request) {
        $notes = Note::all()
            ->where('user_id', $request->user()->id);

        return new NoteCollection($notes);
    });

    Route::get('notes/{id}', function (Request $request) {
        $note = Note::where('user_id', $request->user()->id)->first();

        return new NoteResource($note);
    });
});
