<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\Models\SalesModel;

class SalesController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Transaksi Penjualan',
            'list'  => ['Home', 'Penjualan']
        ];
        $page = (object) [
            'title' => 'Kelola transaksi penjualan'
        ];
        $activeMenu = 'penjualan';

        return view('sales.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request)
    {
        $query = DB::table('t_penjualan')
            ->join('m_user', 't_penjualan.user_id', '=', 'm_user.user_id')
            ->select(
                't_penjualan.penjualan_id',
                't_penjualan.penjualan_kode',
                'm_user.nama as user_nama',
                't_penjualan.pembeli',
                't_penjualan.penjualan_tanggal'
            );
        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button onclick="modalAction(\'' . url('/sales/'.$row->penjualan_id.'/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sales/'.$row->penjualan_id.'/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/sales/'.$row->penjualan_id.'/confirm_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create_ajax()
    {
        $barang = DB::table('m_barang')->select('barang_id','barang_nama','harga_jual')->get();
        return view('sales.create_ajax', compact('barang'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'pembeli'=>'required|string',
                'penjualan_tanggal'=>'required|date',
                'barang_id'=>'required|array|min:1',
                'barang_id.*'=>'required|exists:m_barang,barang_id',
                'jumlah'=>'required|array|min:1',
                'jumlah.*'=>'required|integer|min:1'
            ];
            $v = Validator::make($request->all(), $rules);
            if ($v->fails()) {
                return response()->json(['status'=>false,'message'=>'Validasi gagal','msgField'=>$v->errors()]);
            }
            $last = DB::table('t_penjualan')->latest('penjualan_id')->first();
            $next = $last? ((int)substr($last->penjualan_kode,3)+1) : 1;
            $kode = 'PJL'.str_pad($next,3,'0',STR_PAD_LEFT);
            $id = DB::table('t_penjualan')->insertGetId([
                'user_id'           => Auth::id(),
                'pembeli'           => $request->pembeli,
                'penjualan_kode'    => $kode,
                'penjualan_tanggal' => $request->penjualan_tanggal,
                'created_at'        => now(),
                'updated_at'        => now()
            ]);
            $barangIds = $request->input('barang_id');
            $jumlahs = $request->input('jumlah');
            $hargaJuals = $request->input('harga_jual');
            $details = [];
            foreach ($barangIds as $i => $barangId) {
                $details[] = [
                    'penjualan_id'=>$id,
                    'barang_id'=>$barangId,
                    'harga_jual'=>$hargaJuals[$i] ?? 0,
                    'jumlah'=>$jumlahs[$i],
                    'created_at'=>now(),
                    'updated_at'=>now()
                ];
            }
            DB::table('t_penjualan_detail')->insert($details);
            return response()->json(['status'=>true,'message'=>'Data berhasil disimpan']);
        }
        return redirect('/sales');
    }

    public function show_ajax($id)
    {
        $data = DB::table('t_penjualan')->where('penjualan_id', $id)->first();
        $details = DB::table('t_penjualan_detail')
            ->join('m_barang', 't_penjualan_detail.barang_id', '=', 'm_barang.barang_id')
            ->where('t_penjualan_detail.penjualan_id', $id)
            ->select('t_penjualan_detail.*', 'm_barang.barang_nama')
            ->get();
        return view('sales.show_ajax', ['data' => $data, 'details' => $details]);
    }

    public function edit_ajax($id)
    {
        $data = DB::table('t_penjualan')->where('penjualan_id',$id)->first();
        return view('sales.edit_ajax',['data'=>$data]);
    }

    public function update_ajax(Request $request,$id)
    {
        if ($request->ajax()) {
            $rules = ['pembeli'=>'required|string','penjualan_tanggal'=>'required|date'];
            $v = Validator::make($request->all(), $rules);
            if ($v->fails()) {
                return response()->json(['status'=>false,'message'=>'Validasi gagal','msgField'=>$v->errors()]);
            }
            DB::table('t_penjualan')->where('penjualan_id',$id)->update([
                'pembeli'=>$request->pembeli,
                'penjualan_tanggal'=>$request->penjualan_tanggal,
                'updated_at'=>now()
            ]);
            return response()->json(['status'=>true,'message'=>'Data berhasil diupdate']);
        }
        return redirect('/sales');
    }

    public function confirm_ajax($id)
    {
        $data = DB::table('t_penjualan')->where('penjualan_id',$id)->first();
        return view('sales.delete_ajax',['data'=>$data]);
    }

    public function delete_ajax(Request $request,$id)
    {
        if ($request->ajax()) {
            try {
                DB::transaction(function() use ($id) {
                    DB::table('t_penjualan_detail')->where('penjualan_id',$id)->delete();
                    DB::table('t_penjualan')->where('penjualan_id',$id)->delete();
                });
                return response()->json(['status'=>true,'message'=>'Data berhasil dihapus']);
            } catch (\Exception $e) {
                return response()->json(['status'=>false,'message'=>'Gagal menghapus data: '.$e->getMessage()], 500);
            }
        }
        return redirect('/sales');
    }

    public function export_excel()
    {
        $sales = DB::table('t_penjualan as tp')
            ->join('m_user as mu', 'tp.user_id', '=', 'mu.user_id')
            ->select('tp.penjualan_id', 'tp.penjualan_kode', 'mu.nama as user_nama', 'tp.pembeli', 'tp.penjualan_tanggal')
            ->orderBy('tp.penjualan_tanggal', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Penjualan');
        $sheet->setCellValue('C1', 'User');
        $sheet->setCellValue('D1', 'Pembeli');
        $sheet->setCellValue('E1', 'Tanggal');
        $sheet->setCellValue('F1', 'Total Harga');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($sales as $sale) {
            $totalHarga = DB::table('t_penjualan_detail')
                ->where('penjualan_id', $sale->penjualan_id)
                ->sum(DB::raw('harga_jual * jumlah'));

            $sheet->setCellValue('A' . $baris, $no++);
            $sheet->setCellValue('B' . $baris, $sale->penjualan_kode);
            $sheet->setCellValue('C' . $baris, $sale->user_nama);
            $sheet->setCellValue('D' . $baris, $sale->pembeli);
            $sheet->setCellValue('E' . $baris, $sale->penjualan_tanggal);
            $sheet->setCellValue('F' . $baris, $totalHarga);
            $sheet->getStyle('F' . $baris)->getNumberFormat()->setFormatCode('#,##0');
            $baris++;
        }

        foreach(range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Penjualan');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Penjualan_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        ini_set('max_execution_time', 300);

        $sales = DB::table('t_penjualan as tp')
            ->join('m_user as mu', 'tp.user_id', '=', 'mu.user_id')
            ->select('tp.penjualan_id', 'tp.penjualan_kode', 'mu.nama as user_nama', 'tp.pembeli', 'tp.penjualan_tanggal')
            ->orderBy('tp.penjualan_tanggal', 'desc')
            ->get();

        foreach ($sales as $sale) {
            $sale->total_harga = DB::table('t_penjualan_detail')
                                ->where('penjualan_id', $sale->penjualan_id)
                                ->sum(DB::raw('harga_jual * jumlah'));
        }

        $pdf = Pdf::loadView('sales.export_pdf', ['sales' => $sales]);
        $pdf->setPaper('A4', 'portrait');

        $pdf->setOptions([
            'isRemoteEnabled' => false,
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => false,
            'dpi' => 96,
            'defaultFont' => 'sans-serif'
        ]);

        return $pdf->stream('Data_Penjualan_' . date('Y-m-d_H-i-s') . '.pdf');
    }

    public function import()
    {
        return view('sales.import');
    }

    public function import_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => 'File tidak valid', 'msgField' => ['file' => $validator->errors()->get('file')]]);
        }

        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (count($rows) <= 1) {
                return response()->json(['status' => false, 'message' => 'File tidak memiliki data']);
            }

            DB::beginTransaction();
            try {
                $lastTransaction = DB::table('t_penjualan')->latest('penjualan_id')->first();
                $nextCode = $lastTransaction ? ((int)substr($lastTransaction->penjualan_kode, 3) + 1) : 1;
                $transactionCode = 'PJL'.str_pad($nextCode, 3, '0', STR_PAD_LEFT);
                
                $penjualanId = DB::table('t_penjualan')->insertGetId([
                    'user_id' => Auth::id(),
                    'pembeli' => 'Import System',
                    'penjualan_kode' => $transactionCode, 
                    'penjualan_tanggal' => now(),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $details = [];
                foreach (array_slice($rows, 1) as $row) {
                    $barangId = $row[1];
                    $barang = DB::table('m_barang')->where('barang_id', $barangId)->first();
                    
                    if (!$barang) {
                        continue; 
                    }
                    
                    $details[] = [
                        'penjualan_id' => $penjualanId,
                        'barang_id' => $barangId,
                        'jumlah' => $row[2],
                        'harga_jual' => $row[3],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                
                if (empty($details)) {
                    throw new \Exception('Tidak ada data valid yang dapat diimpor');
                }
                
                DB::table('t_penjualan_detail')->insert($details);
                DB::commit();
                
                return response()->json(['status' => true, 'message' => 'Data transaksi berhasil diimpor']);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Import gagal: ' . $e->getMessage()]);
        }
    }
}
