<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Obtener perfil completo
    public function show($id)
    {
        $user = User::with(['posts'])->findOrFail($id);

        // Contadores
        $user->followers_count = $user->followers()->count();
        $user->following_count = $user->following()->count();

        // ¿El usuario logueado sigue a este?
        $user->is_following = auth()->check()
            ? auth()->user()->isFollowing($id)
            : false;

        return response()->json($user);
    }

    // Actualizar bio
    public function updateBio(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id !== auth()->id()) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $user->bio = $request->bio;
        $user->save();

        return response()->json(['message' => 'Bio actualizada']);
    }
}
