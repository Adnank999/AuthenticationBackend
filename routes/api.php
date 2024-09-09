<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;



Route::post('register', [AuthController::class, 'register']);

Route::post('login', [AuthController::class, 'login'])->name('login');

Route::get('logout', [AuthController::class, 'logout'])->name('logout');


Route::group([ 'middleware' => ['auth:sanctum']], function () {

    /* Roles api */

    Route::post('/roles', [RolesController::class, 'store']); // Add a new role
    Route::get('/getRoles', [RolesController::class, 'getAllRoles']);
    Route::put('/roles/{id}', [RolesController::class, 'update']); // Update a role
    Route::delete('/roles/{id}', [RolesController::class, 'destroy']); // Delete a role

    //         /* end */

    //         /* Permissions api */
    Route::post('/permissions', [PermissionController::class, 'store']); // Add a new permission
    Route::get('/getAllPermissions', [PermissionController::class, 'getAllPermissions']);
    Route::put('/permissions/{id}', [PermissionController::class, 'update']); // Update a permission
    Route::delete('/permissions/{id}', [PermissionController::class, 'destroy']); // Delete a permission
    Route::post('/permissions/assign-role/{user}', [PermissionController::class, 'assignRoleToUser']); // assign only roles
    Route::post('/permissions/revoke', [PermissionController::class, 'revokePermissionFromRole']); // Revoke permissions from a role
    // Route::post('/permissions/assign-role-with-permissions/{user}', [PermissionController::class, 'assignRoleToUserWithPermissions']); /* with roles and permission both */
    Route::post('/permissions/assign-role-with-permissions', [PermissionController::class, 'assignPermissionsToRole']);
    Route::post('/permissions/revoke-role-with-permissions', [PermissionController::class, 'revokePermissionsFromRole']);/* revoke roles and permission both */
    //         /* END */


    Route::post('/addUser', [UserController::class, 'create']);
    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getUser/{id}', [UserController::class, 'getUser']);
    Route::patch('/edit/{id}', [UserController::class, 'update']);
    Route::delete('/delete/{id}', [UserController::class, 'delete']);
});






