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
use Illuminate\Support\Facades\Hash;

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
public function create()
{
    return view('admin.users.create_user');
}

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'confirm_password' => 'required|same:password',
        'phone' => 'sometimes|required|string|max:15|min:10|unique:users,phone|regex:/^[0-9]+$/',
        'address' => 'nullable|string',
        'gender' => 'string|in:female,male',
        'birth_date' => 'nullable|date|date_format:Y-m-d',
        'role' => 'nullable|string|in:admin,user|default:user', 
    ]);

    $validated['password'] = Hash::make($validated['password']);

    User::create($validated);

    return redirect()->route('admin.users.show')->with('success', 'User added successfully!');
}
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
           'token' => $token,
           'user' =>
           [
               
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone,
                'gender' => Auth::user()->gender,
                'address' => Auth::user()->address,
                'birth_date' => Auth::user()->birth_date,
                'phone' => Auth::user()->phone,
                'profile_picture' => Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : null,

           ]
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

        if ($request->hasFile('profile_picture')) 
        {
   
            $imageName = time() . '.' . $request->profile_picture->extension();
            $request->profile_picture->storeAs('profile_picture', $imageName, 'public');
            $full = storage_path('app/public/profile_picture/' . $imageName);
            $user->profile_picture = asset('storage/profile_picture/'.$imageName);

        }
    
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
   
    public function showUsersInDashboard()
    {
        $users = User::all();
        return view('admin.users.show', compact('users'));
    }

    public function edit($id)
{
    $user = User::findOrFail($id);
    return view('admin.users.edit', compact('user'));
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);
    $user->update($request->all());
    return redirect()->route('admin.users.show')->with('success', 'User updated successfully');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    return redirect()->route('admin.users.show')->with('success', 'User deleted successfully');
}

public function loginDashboard(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard'); 
        } else {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }
    }

    return back()->with('error', 'Invalid credentials.');
}

}

    
