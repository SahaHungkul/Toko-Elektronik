<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $validasiRegistrasi = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            $register = User::create([
                'name' => $validasiRegistrasi['name'],
                'email' => $validasiRegistrasi['email'],
                'password' => bcrypt($validasiRegistrasi['password']),
                'role' => 'customer',
            ]);

            $register->assignRole('customer');
            // if(!$register){
            //     return response()->json([
            //         'status' => false,
            //         'message' => '',
            //     ]);
            // }
            return response()->json([
                'status' => true,
                'message' => 'Registrasi berhasil',
                'data' => new UserResource($register),
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function login (Request $request)
    {
        try{
            $validasiLogin = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $login = User::where('email', $validasiLogin['email'])->first();

            if(!$login || !Hash::check($validasiLogin['password'], $login->password)){
                return response()->json([
                    'status' => false,
                    'message' => 'Email atau password salah',
                ],401);
            }

            $token = $login->createToken('Token')->accessToken;

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil',
                'token' => $token,
                'data' => $login,
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Login gagal',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function logout (Request $request)
    {
        try{
            $request->user()->token()->revoke();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil',
            ],200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Logout gagal',
                'error' => $e->getMessage(),
            ],500);
        }
    }

    public function me(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data user berhasil diambil',
            'data' => new UserResource($request->user()),
        ],200);
    }
    public function updateProfile(Request $req)
    {
        try{
            $user = $req->user();
            $req->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,'.$user->id,
                'phone' => 'sometimes|nullable|string|max:20',
            ]);
            $user->update($req->only(['name','email','phone']));

            return response()->json([
                'status' => true,
                'message' => 'profile berhasil diupdate',
                'data' => new UserResource($user),
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'kesalahan pada update profile',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function changePassword(Request $request)
    {
        try{
            $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if(!Hash::check($request->current_password, $request->user()->password)){
                return response()->json([
                    'status' => false,
                    'message' => 'Password saat ini tidak sesuai',
                ], 400);
            }
            $request->user()->update([
                'password' => Hash::make($request->new_password),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah',
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'gagal mengubah password',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
