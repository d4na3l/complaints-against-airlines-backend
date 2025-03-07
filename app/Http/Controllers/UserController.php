<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index () {

        $users = User::all();

        return response()->json([
            'status' => true,
            'message' => 'Lista de usuarios',
            'data' => $users
        ], 200);
    }
}
