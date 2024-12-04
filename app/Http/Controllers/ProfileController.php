<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $profile = 'active';
        $title = 'Gestion du Profile';
        $data = Auth::user();

        return view('dashboard.users.profile', compact('data', 'profile', 'title'));
    }

    public function update(Request $request, $id) {
        // Valider les données entrantes
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'role' => 'required|string',
            'password' => 'nullable|string|min:8',
        ]);

        // Trouver l'utilisateur
        $user = User::findOrFail($id);

        // Mettre à jour les données
        $user->email = $request->email;
        $user->name = $request->name;
        $user->role = $request->role;

        if ($request->password) {
            $user->password = Hash::make($request->password); // Hash le mot de passe
        }

        $user->save();

        // Réponse JSON
        return response()->json([
            'message' => 'Utilisateur mis à jour avec succès',
        ]);
    }
}
