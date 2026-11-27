<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model {
    protected $table    = 'sale_details';
    protected $fillable = ['sale_id','menu_id','qty','harga_satuan','subtotal'];
    protected $casts    = ['harga_satuan'=>'float','subtotal'=>'float'];

    public function sale() { return $this->belongsTo(Sale::class); }
    public function menu() { return $this->belongsTo(Menu::class); }
}
