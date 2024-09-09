<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
   

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6']
        ]);
    
        // Hash the password before creating the user
        $data['password'] = bcrypt($data['password']);
    
   
        $user = User::create($data);
    
        
        auth()->shouldUse('api');  
    
       
        $user->assignRole('User');
       $token = $user->createToken('jwt')->plainTextToken;
    
       
        return [
            'user' => $user,
            'token' => $token
        ];
    }
    



  

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Attempt to log in with the provided credentials
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], Response::HTTP_UNAUTHORIZED);
        }

        // Get the authenticated user
        $user = Auth::user();

        // Get the role name using Spatie's method
        $roleName = $user->getRoleNames()->first(); // Since you're allowing only one role, get the first one

        // Generate the token
        $token = $user->createToken('token')->plainTextToken;

        // Create the cookie (if necessary)
        $cookie = cookie('jwt', $token, 60 * 24); // 1 day

        // Return the response with the token, user, and role name
        return response()->json([
            'message' => 'Logged in successfully',
            'token' => $token,
            'user' => $user,
            'role' => $roleName, // Include the role name in the response
        ], Response::HTTP_OK);
        // ->withCookie($cookie);  // Uncomment if you are using cookies
    }



    public function logout()
    {

        $cookie = Cookie::forget('jwt');
        return response([
            'message' => "logged out"
        ])->withCookie($cookie);
    }
}
