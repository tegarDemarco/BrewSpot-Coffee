<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, AppNotification, BahanBaku};

class NotificationSeeder extends Seeder {
    public function run(): void {
        if (AppNotification::count() > 0) return;

        $owners = User::where('role', 'owner')->get();
        foreach ($owners as $owner) {
            AppNotification::notifyUser(
                $owner->id,
                'Selamat Datang di BrewSpot',
                'Sistem notifikasi real-time & rekap penjualan PDF kini aktif untuk memantau operasional.',
                'info',
                '🔔',
                route('owner.rekap.index')
            );

            $lowStocks = BahanBaku::whereRaw('stok <= stok_minimum')->get();
            foreach ($lowStocks as $b) {
                AppNotification::notifyUser(
                    $owner->id,
                    'Stok Bahan Baku Menipis',
                    "Stok {$b->nama_bahan} tersisa {$b->stok} {$b->satuan} (minimum {$b->stok_minimum} {$b->satuan}).",
                    'warning',
                    '⚠️',
                    route('owner.bahanbaku.edit', $b)
                );
            }
        }

        $drivers = User::where('role', 'driver')->get();
        foreach ($drivers as $driver) {
            AppNotification::notifyUser(
                $driver->id,
                'Jadwal Rute Tersedia',
                'Periksa daftar rute dan titik mangkal Anda hari ini sebelum memulai perjalanan.',
                'info',
                '🗺️',
                route('driver.rute.index')
            );
        }
    }
}
