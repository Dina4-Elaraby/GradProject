<?php

namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
// use Validator;
  use Illuminate\Http\Request;
  use Tymon\JWTAuth\Facades\JWTAuth;
  use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\JWTSubject;

use Illuminate\Support\Facades\Log; 
  
class UserController extends Controller
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
            'status'=> 'required|string|in:admin,user'
           
        ]);
  
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $user = new User;
        $user->name = request()->name;
        $user->email = request()->email;
        $user->password = bcrypt(request()->password);
        $user-> status=request()->status;
        $user->save();
  
        return response()->json($user, 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   // User Login
   public function login(Request $request)
   {
       // Validate the incoming request
       $request->validate([
           'email' => 'required|email',
           'password' => 'required'
       ]);
   
       // Attempt to authenticate and create a token
       $credentials = $request->only('email', 'password');
   
       try {
           // If authentication fails
           if (!$token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'Invalid credentials'], 401);
           }
       } catch (JWTException $e) {
           return response()->json(['error' => 'Could not create token'], 500);
       }
   
       // Return token on success
       return response()->json([
           'message' => 'Login successful',
           'token' => $token
       ]);
   }
   
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
   
// User Logout
public function logout()
{
    try {
        // Invalidate the token
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'message' => 'Logout successful. Token invalidated.'
        ]);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Failed to logout, please try again'], 500);
    }
}

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    public function getUserByToken(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
    } catch (JWTException $e) {
        return response()->json(['error' => 'Token is invalid'], 400);
    }
}}