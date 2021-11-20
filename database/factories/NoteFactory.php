<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tokenable_type' => 2,
            'name' => 'testy.mctestface@example.com',
            'token' => '2fb7d93a6849a004e4a84efda5d0eb121dafb00a7cdbd5dcec2fff0b57c5a90e',
            'abilities' => ['*'],
            'last_used_at' => null,
            'created_at' => (new Carbon())->subMonth(),
            'updated_at' => (new Carbon())->subMonth(),
        ];
    }
}
