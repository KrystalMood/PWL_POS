<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BarangController extends Controller
{
    public function index()
    {
        return BarangModel::with('kategori')->get();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0',
            'barang_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        
        if ($request->hasFile('barang_image')) {
            $file = $request->file('barang_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('images/barang', $filename, 'public');
            $data['barang_image'] = $filePath;
        }

        $barang = BarangModel::create($data);

        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil ditambahkan',
            'data' => $barang->load('kategori'),
        ], 201);
    }

    public function show(BarangModel $barang)
    {
        return $barang->load('kategori');
    }

    public function update(Request $request, BarangModel $barang)
    {
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'nullable|exists:m_kategori,kategori_id',
            'barang_kode' => 'nullable|string|max:10|unique:m_barang,barang_kode,' . $barang->barang_id . ',barang_id',
            'barang_nama' => 'nullable|string|max:100',
            'harga_beli' => 'nullable|integer|min:0',
            'harga_jual' => 'nullable|integer|min:0',
            'barang_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();

        if ($request->hasFile('barang_image')) {
            if ($barang->barang_image) {
                Storage::disk('public')->delete($barang->barang_image);
            }
            
            $file = $request->file('barang_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('images/barang', $filename, 'public');
            $data['barang_image'] = $filePath;
        }

        $barang->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Barang berhasil diupdate',
            'data' => $barang->load('kategori'),
        ], 200);
    }

    public function destroy(BarangModel $barang)
    {
        try {
            if ($barang->barang_image) {
                Storage::disk('public')->delete($barang->barang_image);
            }
            
            $barang->delete();
            return response()->json([
                'status' => true,
                'message' => 'Barang berhasil dihapus',
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal menghapus barang karena terkait dengan data lain (misal: stok atau transaksi).',
                ], 409);
            }
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus barang.',
            ], 500);
        }
    }
    
    public function getImage($id)
    {
        $barang = BarangModel::findOrFail($id);
        
        if (!$barang->barang_image) {
            return response()->json([
                'status' => false,
                'message' => 'Gambar tidak ditemukan',
            ], 404);
        }
        
        $path = Storage::disk('public')->path($barang->barang_image);
        
        if (!file_exists($path)) {
            return response()->json([
                'status' => false,
                'message' => 'File gambar tidak ditemukan',
            ], 404);
        }
        
        $fileContents = file_get_contents($path);
        $mimeType = mime_content_type($path);
        
        return response($fileContents)->header('Content-Type', $mimeType);
    }
}
