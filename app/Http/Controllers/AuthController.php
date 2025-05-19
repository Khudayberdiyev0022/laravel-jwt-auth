<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends \Illuminate\Routing\Controller
{
  public function __construct()
  {
    $this->middleware('auth:api', ['except' => ['login']]);
  }
//  public static function middleware(): array
//  {
//    return [
//      'auth',
//      new Middleware('login', except: ['login']),
//    ];
//  }

  public function login(): JsonResponse
  {
    $credentials = request()->only('email', 'password');

    try {
      if (! $token = JWTAuth::attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }

      // Get the authenticated user.
      $user = auth()->user();

      // (optional) Attach the role to the token.
      $token = JWTAuth::claims(['role' => $user->role])->fromUser($user);

      return $this->respondWithToken($token);
    } catch (JWTException $e) {
      return response()->json(['error' => 'Could not create token'], 500);
    }
//    $credentials = request(['email', 'password']);
//
//    if (!$token = auth()->attempt($credentials)) {
//      return response()->json(['error' => 'Unauthorized'], 401);
//    }
//
//    return $this->respondWithToken($token);
  }

  public function me(): JsonResponse
  {
    return response()->json(auth()->user());
  }

  public function logout(): JsonResponse
  {
    auth()->logout();

    return response()->json(['message' => 'Successfully logged out']);
  }

  public function refresh(): JsonResponse
  {
    return $this->respondWithToken(auth()->refresh());
  }

  protected function respondWithToken($token): JsonResponse
  {
    return response()->json([
      'access_token' => $token,
      'token_type'   => 'bearer',
      'expires_in'   => auth()->factory()->getTTL() * 60,
    ]);
  }
  public function payload(): JsonResponse
  {
    return response()->json(auth()->payload());
  }

}
