<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model {
    use HasFactory;
    protected $table    = 'sales';
    protected $fillable = ['kunjungan_id','nomor_transaksi','total','metode_pembayaran','status','catatan'];
    protected $casts    = ['total'=>'float'];

    public function kunjungan() { return $this->belongsTo(Kunjungan::class); }
    public function details()   { return $this->hasMany(SaleDetail::class); }

    protected static function boot() {
        parent::boot();
        static::creating(function($sale) {
            if (!$sale->nomor_transaksi) {
                $sale->nomor_transaksi = 'TRX-' . strtoupper(uniqid());
            }
        });
    }
}
