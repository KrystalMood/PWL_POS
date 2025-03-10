<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'supplier_id' => 1,
                'supplier_kode' => 'SUP-001',
                'supplier_nama' => 'PT. Supplier Sejahtera',
                'supplier_alamat' => 'Jl. Raya Supplier No. 123, Jakarta',
            ],
            [
                'supplier_id' => 2,
                'supplier_kode' => 'SUP-002',
                'supplier_nama' => 'CV. Makmur Jaya',
                'supplier_alamat' => 'Jl. Pahlawan No. 45, Surabaya',
            ],
            [
                'supplier_id' => 3,
                'supplier_kode' => 'SUP-003',
                'supplier_nama' => 'PT. Barang Berkah',
                'supplier_alamat' => 'Jl. Industri Blok C5, Bandung',
            ],
            [
                'supplier_id' => 4,
                'supplier_kode' => 'SUP-004',
                'supplier_nama' => 'CV. Mitra Usaha',
                'supplier_alamat' => 'Jl. Pemuda No. 78, Semarang',
            ],
        ];
        
        DB::table('m_supplier')->insert($data);
    }
}
