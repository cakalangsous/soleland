<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    use HttpResponses;

    public function profile()
    {
        return $this->success(auth()->user(), 'Get character\'s profile success.');
    }

    public function update(Request $req)
    {
        $validated = $req->validate([
            'level' => 'sometimes|numeric',
            'experience' => 'sometimes|numeric',
            'last_login' => 'sometimes',
            'x_coordinate' => 'sometimes|numeric',
            'y_coordinate' => 'sometimes|numeric'
        ]);

        $character = auth('character')->user();

        $character->level = $validated['level'] ?? $character->level;
        $character->experience = $validated['experience'] ?? $character->experience;
        $character->last_login = $validated['last_login'] ?? $character->last_login;
        $character->x_coordinate = $validated['x_coordinate'] ?? $character->x_coordinate;
        $character->y_coordinate = $validated['y_coordinate'] ?? $character->y_coordinate;

        $character->save();

        return $this->success($character, 'Kid Updated');
    }
}
