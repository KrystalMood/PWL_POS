<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];
        
        $page = (object) [
            'title' => 'Daftar kategori yang terdaftar dalam sistem',
        ];
        
        $activeMenu = 'kategori';
        
        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    
    public function list(Request $request)
    {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode', 'kategori_nama');
        
        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('action', function ($kategori) {
                $btn = '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/kategori/' . $kategori->kategori_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];
        
        $page = (object) [
            'title' => 'Tambah kategori baru',
        ];
        
        $activeMenu = 'kategori';
        
        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|string|max:100',
        ]);
        
        KategoriModel::create([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        
        return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan');

    }
    
    public function show(string $id)
    {
        $kategori = KategoriModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail'],
        ];
        
        $page = (object) [
            'title' => 'Detail kategori',
        ];
        
        $activeMenu = 'kategori';
        
        return view('kategori.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    
    public function edit(string $id)
    {
        $kategori = KategoriModel::find($id);
        
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];
        
        $page = (object) [
            'title' => 'Edit kategori',
        ];
        
        $activeMenu = 'kategori';
        
        return view('kategori.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }
    
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode,' . $id . ',kategori_id',
            'kategori_nama' => 'required|string|max:100',
        ]);
        
        KategoriModel::find($id)->update([
            'kategori_kode' => $request->kategori_kode,
            'kategori_nama' => $request->kategori_nama,
        ]);
        
        return redirect('/kategori')->with('success', 'Kategori berhasil diupdate');
    }
    
    public function destroy(string $id)
    {
        KategoriModel::find($id)->delete();
        
        return redirect('/kategori')->with('success', 'Kategori berhasil dihapus');
    }

    public function create_ajax()
    {
        return view('kategori.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|string|max:10|unique:m_kategori,kategori_kode',
                'kategori_nama' => 'required|string|max:100',
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $kategori = KategoriModel::create($request->all());
            return response()->json([
                'status' => true,
                'message' => 'Data kategori berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function show_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.show_ajax', ['kategori' => $kategori]);
    }

    public function edit_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => "required|string|max:10|unique:m_kategori,kategori_kode,{$id},kategori_id",
                'kategori_nama' => 'required|string|max:100',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }
            $check = KategoriModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $kategori = KategoriModel::find($id);
        return view('kategori.delete_ajax', ['kategori' => $kategori]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $check = KategoriModel::find($id);
            if ($check) {
                try {
                    $check->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } catch (\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data gagal dihapus'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import() {
        return view('kategori.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_kategori' => [
                    'required', 
                    'file', 
                    'mimes:xlsx,xls,csv,spreadsheetml', 
                    'max:1024'
                ]
            ];
            
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            
            try {
                $file = $request->file('file_kategori');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);
                $insert = [];
                
                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) {
                            $insert[] = [
                                'kategori_kode' => $value['A'],
                                'kategori_nama' => $value['B'],
                                'created_at' => now(),
                            ];
                        }
                    }
                    
                    if (count($insert) > 0) {
                        try {
                            KategoriModel::insertOrIgnore($insert);
                            return response()->json([
                                'status' => true,
                                'message' => 'Data berhasil diimport'
                            ]);
                        } catch (\Exception $e) {
                            return response()->json([
                                'status' => false,
                                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                            ]);
                        }
                    }
                    
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }
        
        return redirect('/');
    }
}
