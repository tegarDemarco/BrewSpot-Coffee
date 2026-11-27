<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LokasiMangkal extends Model {
    use HasFactory;
    protected $table    = 'lokasi_mangkal';
    protected $fillable = ['nama_lokasi','alamat','deskripsi','latitude','longitude','is_active'];
    protected $casts    = ['is_active'=>'boolean','latitude'=>'float','longitude'=>'float'];

    public function ruteDetail()  { return $this->hasMany(RuteDetail::class); }
    public function kunjungan()   { return $this->hasMany(Kunjungan::class); }
    public function scopeAktif($q){ return $q->where('is_active', true); }
}
