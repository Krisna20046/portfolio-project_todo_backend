<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }

    public function changeRole(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->role = $request->input('role', 'member');
        $user->save();

        return response()->json(['message' => 'Role updated']);
    }
}
