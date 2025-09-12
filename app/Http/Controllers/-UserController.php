<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $users = User::all();
            return response()->json(['success' => true, 'message' => 'Utilisateurs récupérés avec succès', 'data' => $users]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Échec de la récupération des utilisateurs : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Échec de la validation', 'data' => $validator->errors()], 422);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'username' => $request->username,
            ]);
            return response()->json(['success' => true, 'message' => 'Utilisateur créé avec succès', 'data' => $user], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Échec de la création de l\'utilisateur : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json(['success' => true, 'message' => 'Utilisateur récupéré avec succès', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Utilisateur introuvable : ' . $e->getMessage(), 'data' => null], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:255',
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . $id,
                'password' => 'sometimes|required|string|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Échec de la validation', 'data' => $validator->errors()], 422);
            }

            $userData = $request->all();
            if (isset($userData['password'])) {
                $userData['password'] = Hash::make($userData['password']);
            }

            $user->update($userData);
            return response()->json(['success' => true, 'message' => 'Utilisateur mis à jour avec succès', 'data' => $user]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Échec de la mise à jour de l\'utilisateur : ' . $e->getMessage(), 'data' => null], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['success' => true, 'message' => 'Utilisateur supprimé avec succès', 'data' => null], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Échec de la suppression de l\'utilisateur : ' . $e->getMessage(), 'data' => null], 500);
        }
    }
}
