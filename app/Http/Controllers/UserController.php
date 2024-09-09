<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Routing\Controllers\HasMiddleware;
// use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;

class UserController extends Controller 
{



    public function create(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();


        if (!$user->hasPermissionTo('create-users', 'api')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized, You do not have permission'], 403);
        }

        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'avatar' => 'nullable|string',
            'phone_number' => 'nullable|string|max:15',
        ]);

        // Create the user
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'avatar' => $request->avatar,
            'phone_number' => $request->phone_number,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully.',
            'user' => $newUser
        ], 201);
    }



    public function getUsers()
    {
        $user = Auth::user();
    
        // Check if the authenticated user has the 'view-profile' permission
        if (!$user->hasPermissionTo('view-profile', 'api')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized, You do not have permission'], 403);
        }
    
       
        $users = User::with('roles')->get(); 
    
        return response()->json([
            'status' => 'success',
            'users' => $users
        ], 200);
    }



    public function getUser($id)
    {
        $user = Auth::user();
        if (!$user->hasPermissionTo('view-profile', 'api')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized,You Do not have permission.'], 403);
        }

        $user = User::with('roles')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'user' => $user
        ], 200);
    }




    public function update(Request $request, $id)
    {

        $authUser = Auth::user();


        if ( !$authUser->hasPermissionTo('edit-users', 'api') ) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized, You do not have permission'], 403);
        }


        $user = User::findOrFail($id);


        $data = $request->only(['name', 'email', 'password', 'avatar', 'phone_number']);


        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }


        $user->update($data);


        return response()->json([
            'status' => 'success',
            'message' => 'User updated successfully',
            'user' => $user
        ], 200);
    }



    public function delete($id)
{
    $user = Auth::user();

  
    if ($user->id == $id) {
        return response()->json([
            'status' => 'error',
            'message' => 'You cannot delete yourself.'
        ], 403);
    }

    // dd($user->getRoleNames()); 
    
    // dd($user->hasRole('Admin'));


   
    if (!$user->hasPermissionTo('delete-users','api')) {
        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized, You do not have permission'
        ], 403);
    }

    // Proceed to delete the user
    $userToDelete = User::findOrFail($id);
    $userToDelete->delete();

    return response()->json([
        'status' => 'success',
        'message' => 'User deleted successfully'
    ], 200);
}

}
