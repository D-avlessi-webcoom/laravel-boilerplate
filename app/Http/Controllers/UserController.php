<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            $users = User::all();
            return response()->json([
                'success' => true,
                'data' => $users,
                'message' => 'Liste des objets User récupérée avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la récupération de la liste des objets User',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                //Compléter les règles de validation
            ]);

            $user = User::create($validated);
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Objet User créé avec succès'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la création de l\'objet User',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Objet User récupéré avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la récupération de l\'objet User',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function update(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                //Compléter les règles de validation
            ]);

            $user->update($validated);
            
            return response()->json([
                'success' => true,
                'data' => $user,
                'message' => 'Objet User mis à jour avec succès'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la mise à jour de l\'objet User',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Objet User supprimé avec succès'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Échec de la suppression de l\'objet User',
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }
}