<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('t_penjualan', function (Blueprint $table) {
            $table->string('penjualan_kode', 20)->after('pembeli')->nullable();
        });

        $records = DB::table('t_penjualan')->orderBy('penjualan_id')->get();
        foreach ($records as $index => $row) {
            $kode = 'PJL' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            DB::table('t_penjualan')->where('penjualan_id', $row->penjualan_id)
                ->update(['penjualan_kode' => $kode]);
        }

        Schema::table('t_penjualan', function (Blueprint $table) {
            $table->string('penjualan_kode', 20)->nullable(false)->change();
            $table->unique('penjualan_kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('t_penjualan', function (Blueprint $table) {
            $table->dropUnique(['penjualan_kode']);
            $table->dropColumn('penjualan_kode');
        });
    }
};
