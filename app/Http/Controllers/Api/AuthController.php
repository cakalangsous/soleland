<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlayerRequest;
use App\Models\Player;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(StorePlayerRequest $req)
    {
        $inputs = $req->validated();
        
        $inputs['salt'] = Str::random(25);
        $inputs['password'] = Hash::make($inputs['salt'].$inputs['password'].$inputs['salt']);
        $inputs['email_verify_token'] = Str::random(25);
        
        $player = Player::create($inputs);

        return $this->success($player, 'Registration success. Please verify your email.', 201);
    }

    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $player = Player::whereEmail($req->email)->first();

        if (!$player) {
            return $this->error($req->all(), 'Wrong email or password.', 401);
        }

        if (!Hash::check($player->salt.$req->password.$player->salt, $player->password)) {
            return $this->error($req->all(), 'Wrong email or password.', 401);
        }

        // if ($player->email_verified_at == null) {
        //     return $this->error($req->all(), 'Please verify your email first.', 403);
        // }

        $data = [
            'player' => $player,
            'token' => $player->createToken('Api token of '. $player->username)->plainTextToken
        ];

        return $this->success($data, 'success');
    }
}
