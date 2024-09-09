<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{
    // Add a new permission
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name',
            'guard_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $permission = Permission::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'status' => 'success',
            'permission' => $permission,
        ], 201);
    }

    public function getAllPermissions()
    {
       
        $permissions = Permission::all();

       
        return response()->json(
            $permissions,
            200
        );
    }


    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
            'guard_name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $permission->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        return response()->json([
            'status' => 'success',
            'permission' => $permission,
        ], 200);
    }

  
    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found',
            ], 404);
        }

        $permission->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Permission deleted successfully',
        ], 200);
    }

 

    public function assignRoleToUser(Request $request, $userId)
    {
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role_name)->first();

        
        $user->syncRoles($role);

        return response()->json([
            'status' => 'success',
            'message' => "Role '{$role->name}' assigned to user, and previous roles removed.",
        ], 200);
    }


    public function revokeRoleFromUser(Request $request, $userId)
    {
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($userId);
        $role = Role::where('name', $request->role_name)->first();

      
        if ($user->hasRole($role)) {
            $user->syncRoles([]);

            return response()->json([
                'status' => 'success',
                'message' => "Role '{$role->name}' and any previous roles revoked from user.",
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => "User does not have the role '{$role->name}'.",
        ], 400);
    }




    public function assignPermissionsToRole(Request $request)
    {
        
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

     
        $role = Role::where('name', $request->role_name)->firstOrFail();

       
        $permissions = Permission::whereIn('name', $request->permissions)->get();

       
        $role->syncPermissions($permissions);

        return response()->json([
            'status' => 'success',
            'message' => 'Permissions assigned to role successfully, previous permissions removed.',
        ], 200);
    }


    public function revokePermissionsFromRole(Request $request)
    {
    
        $request->validate([
            'role_name' => 'required|string|exists:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

   
        $role = Role::where('name', $request->role_name)->firstOrFail();

      
        $role->syncPermissions([]); // This removes all permissions assigned to the role

        
        $permissions = Permission::whereIn('name', $request->permissions)->get();
        foreach ($permissions as $permission) {
            $role->revokePermissionTo($permission); // Remove specific permissions from the role
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Previous permissions removed, and specified permissions revoked from role successfully.',
        ], 200);
    }
}
