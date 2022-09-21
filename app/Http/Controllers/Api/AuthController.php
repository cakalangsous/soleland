<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreParentRequest;
use App\Models\Parents;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(StoreParentRequest $req)
    {
        $inputs = $req->validated();
        
        $inputs['salt'] = Str::random(25);
        $inputs['password'] = Hash::make($inputs['salt'].$inputs['password'].$inputs['salt']);
        $inputs['email_verify_token'] = Str::random(25);
        
        $parent = Parents::create($inputs);

        return $this->success($parent, 'Parent registration success.', 201);
    }

    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $parent = Parents::whereEmail($req->email)->first();

        if (!$parent) {
            return $this->error($req->all(), 'Wrong email or password.', 401);
        }

        if (!Hash::check($parent->salt.$req->password.$parent->salt, $parent->password)) {
            return $this->error($req->all(), 'Wrong email or password.', 401);
        }

        // if ($parent->email_verified_at == null) {
        //     return $this->error($req->all(), 'Please verify your email first.', 403);
        // }

        $data = [
            'parent' => $parent,
            'token' => $parent->createToken('Api token of '. $parent->username, ['role:parent'])->plainTextToken
        ];

        return $this->success($data, 'success');
    }
}
