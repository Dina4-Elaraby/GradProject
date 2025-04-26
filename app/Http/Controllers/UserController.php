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
 
    #region register

    public function register(Request $request)
     {
        $request->validate
        ([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'gender' => 'nullable|string',
            'address' => 'required|string',
            'birth_date' => 'nullable|date',
            'phone' => 'nullable|string',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
       
        $user = User::create
        ([
       'name'=>$request->name,
       'email'=>$request->email,
       'address'=>$request->address,
       'gender'=>$request->gender,
       'birth_date'=>$request->birth_date,
       'phone'=>$request->phone,
       'profile_picture'=>$request->profile_picture,

       'password'=> bcrypt($request->password),
       
        ]);
        return response()->json(['message'=>'Your register is cretaed successfully','User'=>$user], 201);
    }
    #endregion

   #region User Login
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
   #endregion
   
   
   //Test by add token to header after login, if user is logged in
    public function me()
    {
        return response()->json(Auth::user());
    }

#region Logout  
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
#endregion


    public function refresh()
    {
        return $this->respondWithToken(JWTAuth::refresh(JWTAuth::getToken()));
    }

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