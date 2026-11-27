<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model {
    use HasFactory;
    protected $fillable = ['nama_menu','deskripsi','harga','kategori','is_active','gambar'];
    protected $casts    = ['is_active'=>'boolean','harga'=>'float'];

    public function saleDetails()  { return $this->hasMany(SaleDetail::class); }
    public function scopeAktif($q) { return $q->where('is_active', true); }
}
