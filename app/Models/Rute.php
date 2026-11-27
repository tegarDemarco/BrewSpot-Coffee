<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rute extends Model {
    use HasFactory;
    protected $table    = 'rute';
    protected $fillable = ['owner_id','driver_id','nama_rute','tanggal_berlaku','status','catatan'];
    protected $casts    = ['tanggal_berlaku'=>'date'];

    public function owner()      { return $this->belongsTo(User::class, 'owner_id'); }
    public function driver()     { return $this->belongsTo(User::class, 'driver_id'); }
    public function details()    { return $this->hasMany(RuteDetail::class)->orderBy('urutan'); }
    public function kunjungan()  { return $this->hasMany(Kunjungan::class); }
}
