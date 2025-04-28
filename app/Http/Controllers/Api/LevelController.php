<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LevelModel;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        return LevelModel::all();
    }

    public function store(Request $request)
    {
        $level = LevelModel::create($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Level berhasil ditambahkan',
            'data' => $level,
        ], 201);
    }

    public function show(LevelModel $level)
    {
        return LevelModel::find($level);
    }

    public function update(Request $request, LevelModel $level)
    {
        $level->update($request->all());
        return response()->json([
            'status' => true,
            'message' => 'Level berhasil diupdate',
            'data' => $level,
        ], 200);
    }

    public function destroy(LevelModel $level)
    {
        $level->delete();
        return response()->json([
            'status' => true,
            'message' => 'Level berhasil dihapus',
        ], 200);
    }
}
