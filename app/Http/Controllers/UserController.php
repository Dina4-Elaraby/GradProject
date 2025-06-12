<?php
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
            'confirm_password' => 'required|same:password',
        ]);
       
        User::create
        ([
       'name'=>$request->name,
       'email'=>$request->email,
       'password'=> bcrypt($request->password),
       
        ]);
        return response()->json(['message'=>'Your register is created successfully'], 201);
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

    public function updateProfile(Request $request)
   {
    try {
        $user = JWTAuth::parseToken()->authenticate();
       
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|min:8',
            'gender' => 'sometimes|required|string|in:female,male',
            'address' => 'sometimes|required|string',
            'birth_date' => 'sometimes|required|date|date_format:Y-m-d',
            'phone' => 'sometimes|required|string|max:15|min:10|unique:users,phone,' . $user->id . '|regex:/^[0-9]+$/',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        
        if ($request->has('gender')) 
        {
           $user->gender = $request->gender;
        }

        // Only update fields that are present in the request
        $user->fill($request->only([
            'name',
            'email',
            'gender',
            'address',
            'birth_date',
            'phone',
            'profile_picture'
        ]));

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->hasFile('profile_picture')) {
    // Delete old profile picture if it exists
    if ($user->profile_picture) {
        $oldImagePath = str_replace(asset('storage/'), '', $user->profile_picture);
        Storage::disk('public')->delete($oldImagePath);
    }

    // Store the image and save relative path
    $path = $request->file('profile_picture')->store('profile_pictures', 'public');
   
    $user->profile_picture = $path; // Store relative path
}
    // if ($request->hasFile('profile_picture')) 
    // {
    //     // Store the image in the public disk
    //     $path = $request->file('profile_picture')->store('profile_picture', 'public');

    //     // Get full URL of the image
    //     $imageUrl = asset('storage/' . $path);
    //     $user->profile_picture = $imageUrl;
       
    // } 
     $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user,
            
        ], 200);

}
catch (\Exception $e) 
    {
        return response()->json(['error' => 'Failed to update profile: ' . $e->getMessage()], 500);
    }
   }
   
}

    
