<?php

namespace App\Models;

use App\Http\Requests\NoteRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends AbstractModel
{
    use HasFactory;

    protected $fillable = [
        'title',
        'note',
        'user_id',
    ];


    /**
     * {@inheritdoc}
     */
    public function getRules(): array
    {
        return (new NoteRequest())->rules();
    }

    /**
     * @return BelongsTo
     */
    public function notes(): BelongsTo
    {
        $this->belongsTo(User::class);
    }
}
