<?php

namespace App\Http\Controllers\api;

use App\Http\Resources\UserResource;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    use apiResponseTrait;
    public function login(Request $request)
    {
        //date_default_timezone_set('Africa/Cairo');
        $user = User::where('email', $request->email)->where('state', '!=', 'delete')->first();
        if ($user) {
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return $this->apiResponse('Unauthorized user ', [], [], 1, 401);
            }
            $user = $request->user();
            Auth::user()->tokens->each(function ($token, $key) {
                $token->delete();
            });
            /** @var \App\Models\User $user **/
            $user = Auth::user();
            $token = $user->createToken('system1')->accessToken;
            $user->update(['login' => 'true']);
            return $this->apiResponse('accessed', [
                'token'      => $token,
                'token_type' => 'Bearer',
                'state'      => $user->state,
            ], $user, 1, 201);
        } else {
            return $this->apiResponse('This email not found. please create new account', [], [], [], 404);
        }
    }

    public function register(UserRequest $request)
    {
        //date_default_timezone_set('Africa/Cairo');
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
        ]);
        $token = $user->createToken('API Token')->accessToken;

        return response(['token' => $token, 'user' => new UserResource($user)]);
    }

    public function logout(Request $request)
    {
        User::where('id', $request->user()->id)->update(['login' => 'false']);
        $user = $request->user()->token();
        if ($user) {
            $user->revoke();
            return Response('✔ logout success');
        } else {
            return $this->apiResponse('Error', [], [], [], 404);
        }
    }
}
