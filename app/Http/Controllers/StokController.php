<?php

namespace App\Http\Controllers;

use App\Models\BarangModel;
use App\Models\StokModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class StokController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Stok Barang',
            'list' => ['Home', 'Stok']
        ];

        $page = (object) [
            'title' => 'Daftar stok barang yang tersedia',
        ];

        $activeMenu = 'stok';

        return view('stok.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function list(Request $request)
    {
        $stok = StokModel::with(['barang', 'supplier', 'user']);

        if ($request->has('barang_id') && !empty($request->barang_id)) {
            $stok->where('barang_id', $request->barang_id);
        }

        if ($request->has('supplier_id') && !empty($request->supplier_id)) {
            $stok->where('supplier_id', $request->supplier_id);
        }

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('action', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $stok = StokModel::with(['barang', 'supplier', 'user'])->find($id);
        return view('stok.show_ajax', ['stok' => $stok]);
    }

    public function create_ajax()
    {
        $barang = BarangModel::all();
        $supplier = SupplierModel::all();
        return view('stok.create_ajax', compact('barang', 'supplier'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'barang_id' => 'required|integer|exists:m_barang,barang_id',
                'supplier_id' => 'required|integer|exists:m_supplier,supplier_id',
                'stok_tanggal' => 'required|date',
                'stok_jumlah' => 'required|integer|min:1',
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }
            $data = $request->only(['barang_id', 'supplier_id', 'stok_tanggal', 'stok_jumlah']);
            $data['user_id'] = auth()->id();
            StokModel::create($data);
            return response()->json([
                'status' => true,
                'message' => 'Data stok berhasil disimpan',
            ]);
        }
        return redirect('/');
    }

    public function confirm_ajax($id)
    {
        $stok = StokModel::with(['barang', 'supplier', 'user'])->find($id);
        return view('stok.delete_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                try {
                    $stok->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data stok berhasil dihapus'
                    ]);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data stok gagal dihapus'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data stok tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import()
    {
        return view('stok.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_stok' => [
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
                $file = $request->file('file_stok');
                $reader = IOFactory::createReader('Xlsx');
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file->getRealPath());
                $sheet = $spreadsheet->getActiveSheet();
                $data = $sheet->toArray(null, false, true, true);
                $insert = [];
                $userId = auth()->id();

                if (count($data) > 1) {
                    foreach ($data as $baris => $value) {
                        if ($baris > 1) {
                            if (empty($value['A']) || empty($value['B']) || empty($value['C']) || empty($value['D'])) {
                                continue;
                            }
                            $insert[] = [
                                'barang_id' => $value['A'],
                                'supplier_id' => $value['B'],
                                'stok_tanggal' => $value['C'],
                                'stok_jumlah' => $value['D'],
                                'user_id' => $userId,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    if (count($insert) > 0) {
                        try {
                            StokModel::insert($insert);
                            return response()->json([
                                'status' => true,
                                'message' => 'Data stok berhasil diimport'
                            ]);
                        } catch (\Exception $e) {

                            return response()->json([
                                'status' => false,
                                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
                            ]);
                        }
                    } else {
                         return response()->json([
                            'status' => false,
                            'message' => 'Tidak ada data valid untuk diimport dalam file.'
                        ]);
                    }

                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data yang diimport (file kosong atau hanya header)'
                    ]);
                }
            } catch (\Exception $e) {

                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat membaca file: ' . $e->getMessage()
                ]);
            }
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $stok = StokModel::with(['barang', 'supplier', 'user'])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Barang');
        $sheet->setCellValue('C1', 'Supplier');
        $sheet->setCellValue('D1', 'Tanggal');
        $sheet->setCellValue('E1', 'Jumlah');
        $sheet->setCellValue('F1', 'User Input');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);


        $no = 1;
        $baris = 2;
        foreach ($stok as $value) {
            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $value->barang ? $value->barang->barang_nama : 'N/A');
            $sheet->setCellValue('C' . $baris, $value->supplier ? $value->supplier->supplier_nama : 'N/A');
            $sheet->setCellValue('D' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('E' . $baris, $value->stok_jumlah);
            $sheet->setCellValue('F' . $baris, $value->user ? $value->user->nama : 'N/A');
            $baris++;
        }


        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Stok');


        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Stok_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        ini_set('max_execution_time', 300);

        $stok = StokModel::with(['barang', 'supplier', 'user'])->orderBy('stok_tanggal', 'desc')->get();

        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('A4', 'portrait');

        $pdf->setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'dpi' => 96,
            'defaultFont' => 'sans-serif'
        ]);

        return $pdf->stream('Data_Stok_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
