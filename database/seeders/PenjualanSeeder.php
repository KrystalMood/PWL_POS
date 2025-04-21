<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = DB::table('m_user')->pluck('user_id')->all();
        $barangIds = DB::table('m_barang')->pluck('barang_id')->all();

        $penjualans = [];
        for ($i = 1; $i <= 10; $i++) {
            $penjualans[] = [
                'user_id'           => $userIds[array_rand($userIds)],
                'pembeli'           => 'Customer ' . $i,
                'penjualan_kode'    => 'PJL' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'penjualan_tanggal' => now()->subDays(rand(1, 30)),
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }
        DB::table('t_penjualan')->insert($penjualans);

        $records = DB::table('t_penjualan')
            ->whereIn('penjualan_kode', array_column($penjualans, 'penjualan_kode'))
            ->get();

        $details = [];
        foreach ($records as $penjualan) {
            shuffle($barangIds);
            $count = rand(1, 3);
            for ($j = 0; $j < $count; $j++) {
                $idBarang = $barangIds[$j];
                $harga   = DB::table('m_barang')->where('barang_id', $idBarang)->value('harga_jual');

                $details[] = [
                    'penjualan_id' => $penjualan->penjualan_id,
                    'barang_id'    => $idBarang,
                    'jumlah'       => rand(1, 5),
                    'harga_jual'   => $harga,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }
        }
        DB::table('t_penjualan_detail')->insert($details);
    }
}
