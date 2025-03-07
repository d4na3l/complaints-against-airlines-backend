<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(UserRequest $request)
    {
        try {
            //Validación
            $validateUser = Validator::make($request->all(),
            [
                `first_name` => 'max:50',
                `last_name` => 'max:50',
                `birthdate` => 'date',
                `password` => 'password|max:255',
                `document` => 'max:20',
                `email` => 'unique:users,email|required',
                `phone` => 'nullable|max:20|min:11',
                `local_phone` => 'nullable|max:20|min:11',
                `profession` => 'nullable|max:100',
                `domicile_address` => 'nullable|max:255',
                'additional_address' => 'nullable|max:255'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error en validación',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            //Creación de Usuario
            $user = User::create([
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
                "birthdate" => $request->birthdate,
                "password" => Hash::make($request->password),
                "document" => $request->document,
                "email" => $request->email,
                "phone" => $request->phone,
                "local_phone" => $request->local_phone,
                "profession" => $request->profession,
                "domicile_address" => $request->domicile_address,
                "additional_address" => $request->additional_address,
                "document_type_id" => $request->document_type_id,
                "role_id" => $request->role_id,
                "nationality_id" => $request->nationality_id,
                "country_origin_id" => $request->country_origin_id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Usuarrio creado',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error en validación',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'El correo y/o contraseña no existe.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'Has iniciado sesión',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
