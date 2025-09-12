<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class RolePermissionController extends Controller
{
    public function assignPermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $permissions = Permission::whereIn('name', $request->permissions)->get();
        $role->syncPermissions($permissions);

        return response()->json([
            'message' => 'Permissions attribuées avec succès',
            'role' => $role->load('permissions'),
        ]);
    }

    public function getPermissionsByGroup()
    {
        $permissions = Permission::all()->groupBy('group');

        return response()->json($permissions);
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:roles',
            'label' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role = Role::create([
            'name' => $request->name,
            'label' => $request->label,
            'guard_name' => 'web',
        ]);

        return response()->json([
            'message' => 'Rôle créé avec succès',
            'role' => $role,
        ], 201);
    }

    public function show($id)
    {
        $role = Role::with('permissions')->find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        return response()->json($role);
    }

    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255|unique:roles,name,' . $id,
            'label' => 'sometimes|required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $role->update($request->only('name', 'label'));

        return response()->json([
            'message' => 'Rôle mis à jour avec succès',
            'role' => $role,
        ]);
    }

    public function destroy($id)
    {
        if (!Auth::user()->hasRole('Administrateur')) {
            return response()->json(['message' => 'Vous n\'avez pas la permission de supprimer ce rôle.'], 403);
        }

        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'Rôle introuvable'], 404);
        }

        if (User::role($role->name)->count() > 0) {
            return response()->json(['message' => 'Impossible de supprimer un rôle assigné à des utilisateurs'], 403);
        }

        $role->delete();

        return response()->json(['message' => 'Rôle supprimé avec succès']);
    }
}
