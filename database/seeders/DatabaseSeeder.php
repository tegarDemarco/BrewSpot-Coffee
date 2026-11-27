<?php
namespace Database\Seeders;
use App\Models\{User, LokasiMangkal, Menu, Rute, RuteDetail, Kunjungan, Sale, SaleDetail};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // Users
        $owner = User::create([
            'name' => 'Pemilik BrewSpot', 'email' => 'owner@brewspot.id',
            'password' => Hash::make('password'), 'role' => 'owner', 'is_active' => true,
        ]);
        $driver1 = User::create([
            'name' => 'Budi Santoso', 'email' => 'budi@brewspot.id',
            'password' => Hash::make('password'), 'role' => 'driver', 'is_active' => true, 'phone' => '081234567890',
        ]);
        $driver2 = User::create([
            'name' => 'Siti Rahayu', 'email' => 'siti@brewspot.id',
            'password' => Hash::make('password'), 'role' => 'driver', 'is_active' => true, 'phone' => '082345678901',
        ]);

        // Lokasi
        $lokasiData = [
            ['Area Kampus', 'Jl. Kampus Raya No. 1, Kota', 'Dekat area mahasiswa, ramai pagi dan siang'],
            ['Area Perkantoran', 'Jl. Sudirman No. 45, Kota', 'Kawasan perkantoran, ramai jam makan siang'],
            ['Alun-alun Kota', 'Alun-alun Utama Kota', 'Lokasi strategis, ramai sore dan malam'],
            ['Pasar Pagi', 'Jl. Pasar No. 10, Kota', 'Pasar tradisional, ramai pagi hari'],
            ['Taman Kota', 'Jl. Taman Indah No. 5', 'Dekat taman, cocok untuk santai sore'],
        ];
        $lokasi = [];
        foreach ($lokasiData as $l) {
            $lokasi[] = LokasiMangkal::create(['nama_lokasi'=>$l[0],'alamat'=>$l[1],'deskripsi'=>$l[2],'is_active'=>true]);
        }

        // Menu
        $menuData = [
            ['Kopi Hitam', 'Kopi hitam original tanpa gula', 8000, 'kopi'],
            ['Kopi Susu', 'Kopi susu manis creamy', 12000, 'kopi'],
            ['Cappuccino', 'Cappuccino dengan foam susu', 15000, 'kopi'],
            ['Es Kopi', 'Kopi dingin segar', 13000, 'kopi'],
            ['Teh Tarik', 'Teh susu kental manis tarik', 8000, 'non-kopi'],
            ['Cokelat Panas', 'Minuman cokelat hangat', 12000, 'non-kopi'],
        ];
        $menus = [];
        foreach ($menuData as $m) {
            $menus[] = Menu::create(['nama_menu'=>$m[0],'deskripsi'=>$m[1],'harga'=>$m[2],'kategori'=>$m[3],'is_active'=>true]);
        }

        // Rute + data historis
        $dates = [Carbon::now()->subDays(7), Carbon::now()->subDays(3), Carbon::now()->subDays(1), Carbon::today()];
        foreach ($dates as $date) {
            $rute = Rute::create([
                'owner_id'=>$owner->id,'driver_id'=>$driver1->id,
                'nama_rute' => 'Rute Harian ' . $date->format('d M'),
                'tanggal_berlaku' => $date->format('Y-m-d'),
                'status' => $date->isToday() ? 'aktif' : 'selesai',
            ]);
            $selectedLokasi = [$lokasi[0], $lokasi[1], $lokasi[2]];
            foreach ($selectedLokasi as $idx => $lok) {
                RuteDetail::create([
                    'rute_id' => $rute->id,
                    'lokasi_mangkal_id' => $lok->id,
                    'urutan' => $idx + 1,
                    'jam_rencana_mulai'  => ['08:00:00','11:00:00','16:00:00'][$idx],
                    'jam_rencana_selesai'=> ['10:00:00','13:00:00','18:00:00'][$idx],
                ]);
            }
            if (!$date->isToday()) {
                foreach ($selectedLokasi as $lok) {
                    $waktuMulai = Carbon::parse($date->format('Y-m-d') . ' 08:00:00');
                    $k = Kunjungan::create([
                        'driver_id' => $driver1->id,
                        'rute_id'   => $rute->id,
                        'lokasi_mangkal_id' => $lok->id,
                        'waktu_mulai'  => $waktuMulai,
                        'waktu_selesai'=> $waktuMulai->copy()->addHours(2),
                        'status' => 'selesai',
                    ]);
                    // Buat 2–4 transaksi per kunjungan
                    for ($t = 0; $t < rand(2,4); $t++) {
                        $m1 = $menus[rand(0,3)]; $m2 = $menus[rand(0,5)];
                        $qty1 = rand(1,3); $qty2 = rand(1,2);
                        $total = ($m1->harga * $qty1) + ($m2->harga * $qty2);
                        $sale = Sale::create([
                            'kunjungan_id' => $k->id,
                            'total' => $total,
                            'metode_pembayaran' => ['tunai','qris'][rand(0,1)],
                            'status' => 'valid',
                        ]);
                        SaleDetail::create(['sale_id'=>$sale->id,'menu_id'=>$m1->id,'qty'=>$qty1,'harga_satuan'=>$m1->harga,'subtotal'=>$m1->harga*$qty1]);
                        SaleDetail::create(['sale_id'=>$sale->id,'menu_id'=>$m2->id,'qty'=>$qty2,'harga_satuan'=>$m2->harga,'subtotal'=>$m2->harga*$qty2]);
                    }
                }
            }
        }
    }
}
