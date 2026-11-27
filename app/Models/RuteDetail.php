<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuteDetail extends Model {
    protected $table    = 'rute_detail';
    protected $fillable = ['rute_id','lokasi_mangkal_id','urutan','jam_rencana_mulai','jam_rencana_selesai'];

    public function rute()          { return $this->belongsTo(Rute::class); }
    public function lokasiMangkal() { return $this->belongsTo(LokasiMangkal::class); }
    public function kunjungan()     { return $this->hasMany(Kunjungan::class); }
}
