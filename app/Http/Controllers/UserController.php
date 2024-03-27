<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function display()
    {
        $users = User::get();
        // dd($users);
        return response()->json($users, 200);
    }

    public function create(Request $request)
{
    $validate = $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $data = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    if ($data) {
        return response()->json($data, 201);
    } else {
        return response()->json(['message' => 'Erreur lors de la creation'], 500);
    }
}
public function modify(Request $request, $id)
{
    $validate = $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'required',
    ]);

    $data = User::find($id);
    $data->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    if ($data) {
        return response()->json(['message' => 'Modification effectuée avec succès'], 201);
    } else {
        return response()->json(['message' => 'Aucune modification effectuée'], 500);
    }
}

public function delete($id)
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Utilisateur non trouvé'], 404);
    }

    $user->delete();

    if ($user->trashed()) {
        return response()->json(['message' => 'Utilisateur supprimé avec succès'], 200);
    } else {
        return response()->json(['message' => 'Échec de la suppression de l\'utilisateur'], 500);
    }
}

}


