<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required|string|min:5|confirmed',
            'level_id' => 'required',
            'image' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors(),
            ], 422);
        };

        $image = $request->file('image');
        $image->storeAs('public/posts', $image->hashName());

        $user = UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
            'image' => $image->hashName(),
        ]);

        if ($user) {
            return response()->json([
                'status' => true,
                'message' => 'Pembuatan user berhasil dibuat',
                'user' => $user,
            ], 201);
        }

        return response()->json([
            'status' => false,
            'message' => 'Pembuatan user gagal',
        ], 409);
    }
}
