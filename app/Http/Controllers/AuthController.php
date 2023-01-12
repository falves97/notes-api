<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginAuthRequest;
use App\Http\Requests\RegisterAuthRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function __construct() {
        $this->middleware( 'auth:api', [ 'except' => [ 'login', 'register' ] ] );
    }

    public function login( LoginAuthRequest $request ) {
        $credentials = $request->only( 'email', 'password' );

        $token = Auth::attempt( $credentials );
        if ( ! $token ) {
            return response()->json( [
                'status'  => 'error',
                'message' => 'Unauthorized',
            ], 401 );
        }

        $user = Auth::user();

        return response()->json( [
            'status'        => 'success',
            'user'          => $user,
            'authorisation' => [
                'token' => $token,
                'type'  => 'bearer',
            ]
        ] );

    }

    public function register( RegisterAuthRequest $request ) {
        $user = User::create( [
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make( $request->password ),
        ] );

        $token = Auth::login( $user );

        return response()->json( [
            'status'        => 'success',
            'message'       => 'User created successfully',
            'user'          => $user,
            'authorisation' => [
                'token' => $token,
                'type'  => 'bearer',
            ]
        ] );
    }

    public function logout() {
        Auth::logout();

        return response()->json( [
            'status'  => 'success',
            'message' => 'Successfully logged out',
        ] );
    }

    public function refresh() {
        return response()->json( [
            'status'        => 'success',
            'user'          => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type'  => 'bearer',
            ]
        ] );
    }
}
