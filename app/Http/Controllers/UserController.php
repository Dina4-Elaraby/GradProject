<?php
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
 
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
     {
        $request->validate
        ([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            //'role'=> 'required|string|in:admin,user,guest,superadmin,default:guest'         
        ]);
       
        $user = User::create
        ([
       'name'=>$request->name,
       'email'=>$request->email,
       'password'=> bcrypt($request->password),
       //'role'=>$request->role
        ]);
        return response()->json(['message'=>'Your register is cretaed successfully','User'=>$user], 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
   // User Login
   public function login(Request $request)
   {
       $request->validate
       ([
           'email' => 'required|email',
           'password' => 'required'
       ]);
   
       // Attempt to authenticate and create a token
       $credentials = $request->only('email', 'password');
       try 
       {
           // If authentication fails
           if (!$token = JWTAuth::attempt($credentials)) {
               return response()->json(['error' => 'Invalid credentials'], 401);
           }
       }
      catch (JWTException $e) 
       {
           return response()->json(['error' => 'Could not create token'], 500);
       }
       return response()->json
       ([
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
        return response()->json(Auth::user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
   
public function logout()
{
    try 
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json
        ([
            'message' => 'Logout successful. Token invalidated.'
        ]);
    } 
    catch (JWTException $e)
    {
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
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
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
        return response()->json
        ([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function getUserByToken(Request $request)
    {
      try 
       {
        $user = JWTAuth::parseToken()->authenticate();
        
        if (!$user) 
        {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(['user' => $user], 200);
       } 
      catch (JWTException $e) 
      {
        return response()->json(['error' => 'Token is invalid'], 400);
      }

      
}
    public function getData()
      {
        $user = User::all();
        return response()->json($user, 200);
      }
    }