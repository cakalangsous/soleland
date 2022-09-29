<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCharacterRequest;
use App\Models\Character;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class KidAuthController extends Controller
{
    use HttpResponses;

    public function register(StoreCharacterRequest $req)
    {
        $inputs = $req->validated();
        
        $inputs['salt'] = Str::random(25);
        $inputs['password'] = Hash::make($inputs['salt'].$inputs['password'].$inputs['salt']);

        $characters = Character::create($inputs);

        return $this->success($characters, 'Character registration success.', 201);
    }

    public function login(Request $req)
    {
        $req->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $character = Character::where('username', $req->username)->first();

        if (!$character) {
            return $this->error($req->all(), 'Wrong username or password.', 203);
        }

        if (!Hash::check($character->salt.$req->password.$character->salt, $character->password)) {
            return $this->error($req->all(), 'Wrong username or password.', 203);
        }
        
        $data = [
            'character' => $character,
            'token' => $character->createToken('Api token of '. $character->username, ['role:character'])->plainTextToken
        ];

        return $this->success($data, 'Login success');
    }
}
