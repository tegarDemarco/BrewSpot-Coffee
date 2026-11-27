<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BahanBaku extends Model {
    use HasFactory;
    protected $table    = 'bahan_baku';
    protected $fillable = ['nama_bahan','stok','stok_minimum','satuan','harga_satuan','keterangan'];
    protected $casts    = ['stok'=>'float','stok_minimum'=>'float','harga_satuan'=>'float'];

    public function getIsLowAttribute(): bool { return $this->stok <= $this->stok_minimum; }
}
