<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Owner;
use App\Http\Controllers\Driver;

Route::get('/', fn() => redirect()->route('login'));

// ---- AUTH ----
Route::get('/login',  [LoginController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout',[LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ---- NOTIFICATIONS (Owner & Driver) ----
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'read'])->name('read');
    Route::post('/read-all', [App\Http\Controllers\NotificationController::class, 'readAll'])->name('read-all');
});

// ---- OWNER ----
Route::prefix('owner')->name('owner.')->middleware(['auth','owner'])->group(function () {

    Route::get('/dashboard', [Owner\DashboardController::class, 'index'])->name('dashboard');

    // Driver management
    Route::resource('driver', Owner\DriverController::class)->except('destroy','show');
    Route::patch('driver/{driver}/status', [Owner\DriverController::class, 'toggleStatus'])->name('driver.toggle-status');

    // Lokasi Mangkal
    Route::resource('lokasi', Owner\LokasiController::class)->except('destroy','show');
    Route::patch('lokasi/{lokasi}/status', [Owner\LokasiController::class, 'toggleStatus'])->name('lokasi.toggle-status');

    // Menu
    Route::resource('menu', Owner\MenuController::class)->except('destroy','show');
    Route::patch('menu/{menu}/status', [Owner\MenuController::class, 'toggleStatus'])->name('menu.toggle-status');

    // Rute
    Route::resource('rute', Owner\RuteController::class)->except('edit','update');

    // Kunjungan
    Route::get('kunjungan',              [Owner\KunjunganController::class, 'index'])->name('kunjungan.index');
    Route::get('kunjungan/{kunjungan}',  [Owner\KunjunganController::class, 'show'])->name('kunjungan.show');
    Route::patch('kunjungan/{kunjungan}/selesai', [Owner\KunjunganController::class,'forceSelesai'])->name('kunjungan.force-selesai');

    // Transaksi (new page)
    Route::get('transaksi',             [Owner\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/{sale}',      [Owner\TransaksiController::class, 'show'])->name('transaksi.show');
    Route::patch('transaksi/{sale}/cancel', [Owner\TransaksiController::class, 'cancel'])->name('transaksi.cancel');

    // Bahan Baku (new feature)
    Route::resource('bahanbaku', Owner\BahanBakuController::class)->except('show');

    // Laporan & Rekap Penjualan
    Route::get('laporan', [Owner\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('rekap',     [Owner\RekapController::class, 'index'])->name('rekap.index');
    Route::get('rekap/pdf', [Owner\RekapController::class, 'pdf'])->name('rekap.pdf');

    // Pengaturan
    Route::get('pengaturan',          [Owner\PengaturanController::class, 'index'])->name('pengaturan');
    Route::put('pengaturan/profile',  [Owner\PengaturanController::class, 'updateProfile'])->name('pengaturan.update-profile');
    Route::put('pengaturan/password', [Owner\PengaturanController::class, 'updatePassword'])->name('pengaturan.update-password');
});

// ---- DRIVER ----
Route::prefix('driver')->name('driver.')->middleware(['auth','driver.only'])->group(function () {

    Route::get('/dashboard', [Driver\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/rute',       [Driver\RuteSayaController::class, 'index'])->name('rute.index');
    Route::get('/rute/{rute}',[Driver\RuteSayaController::class, 'show'])->name('rute.show');

    Route::get('/kunjungan/aktif',       [Driver\KunjunganController::class, 'aktif'])->name('kunjungan.aktif');
    Route::post('/kunjungan/mulai',      [Driver\KunjunganController::class, 'mulai'])->name('kunjungan.mulai');
    Route::post('/kunjungan/{kunjungan}/selesai', [Driver\KunjunganController::class, 'selesai'])->name('kunjungan.selesai');
    Route::post('/kunjungan/transaksi',  [Driver\KunjunganController::class,'inputTransaksi'])->name('kunjungan.transaksi');

    Route::get('/riwayat',               [Driver\KunjunganController::class,'riwayat'])->name('riwayat');
    Route::get('/riwayat/{kunjungan}',   [Driver\KunjunganController::class,'showKunjungan'])->name('kunjungan.show');
});
