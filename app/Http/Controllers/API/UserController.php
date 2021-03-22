<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * login
     *
     * @param  mixed $request
     * @return void
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string']
        ]);

        if($validator->fails()){
            return $this->responseError('login failed', 422, $validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();

            // buat token nya
            $response = [
                'token' => $user->createToken('MyToken')->accessToken,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ];

            return $this->responseOk($response);
        }else {
            return $this->responseError('Wrong email or password', 401);
        }
    }

    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if($validator->fails()){
            return $this->responseError('Registration Failed', 422, $validator->errors());
        }

        $params = $request->except('password_confirmation');
        $params['password'] = Hash::make($request->password);

        if($user = User::create($params)){
            $token = $user->createToken('MyToken')->accessToken;

            $response = [
                'token' => $token,
                'user' => $user
            ];

            return $this->responseOk($response);
        }else {
            $this->responseError('Registration failed', 400);
        }
    }

    /**
     * profile
     *
     * @param  mixed $request
     * @return void
     */
    public function profile(Request $request)
    {
        return $this->responseOk($request->user());
    }

    /**
     * logout
     *
     * @return void
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->responseOk(null, 200, 'Logged Out Successfully');
    }
}
