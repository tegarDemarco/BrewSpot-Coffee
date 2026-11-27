<?php
namespace Database\Seeders;
use App\Models\BahanBaku;
use Illuminate\Database\Seeder;

class BahanBakuSeeder extends Seeder {
    public function run(): void {
        $data = [
            ['nama_bahan'=>'Kopi Arabika','stok'=>5.0,'stok_minimum'=>2.0,'satuan'=>'kg','harga_satuan'=>120000,'keterangan'=>'Kopi arabika pilihan dari Aceh'],
            ['nama_bahan'=>'Kopi Robusta','stok'=>3.5,'stok_minimum'=>2.0,'satuan'=>'kg','harga_satuan'=>85000,'keterangan'=>'Kopi robusta kualitas premium'],
            ['nama_bahan'=>'Susu Full Cream','stok'=>4.0,'stok_minimum'=>3.0,'satuan'=>'liter','harga_satuan'=>18000,'keterangan'=>'Susu segar untuk kopi susu'],
            ['nama_bahan'=>'Gula Pasir','stok'=>8.0,'stok_minimum'=>3.0,'satuan'=>'kg','harga_satuan'=>15000,'keterangan'=>'Gula pasir putih halus'],
            ['nama_bahan'=>'Cup Plastik 16oz','stok'=>150,'stok_minimum'=>100,'satuan'=>'pcs','harga_satuan'=>800,'keterangan'=>'Cup untuk minuman dingin'],
            ['nama_bahan'=>'Cup Kertas 12oz','stok'=>80,'stok_minimum'=>100,'satuan'=>'pcs','harga_satuan'=>950,'keterangan'=>'Cup untuk minuman panas - STOK RENDAH'],
            ['nama_bahan'=>'Sirup Cokelat','stok'=>1.5,'stok_minimum'=>0.5,'satuan'=>'liter','harga_satuan'=>65000,'keterangan'=>'Sirup cokelat Torani'],
            ['nama_bahan'=>'Es Batu','stok'=>20.0,'stok_minimum'=>10.0,'satuan'=>'kg','harga_satuan'=>2000,'keterangan'=>'Es batu kristal bersih'],
        ];
        foreach ($data as $d) BahanBaku::firstOrCreate(['nama_bahan'=>$d['nama_bahan']], $d);
    }
}
