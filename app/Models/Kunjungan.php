<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kunjungan extends Model {
    use HasFactory;
    protected $table    = 'kunjungan';
    protected $fillable = ['driver_id','rute_id','rute_detail_id','lokasi_mangkal_id','waktu_mulai','waktu_selesai','status','catatan'];
    protected $casts    = ['waktu_mulai'=>'datetime','waktu_selesai'=>'datetime'];

    public function driver()        { return $this->belongsTo(User::class, 'driver_id'); }
    public function rute()          { return $this->belongsTo(Rute::class); }
    public function ruteDetail()    { return $this->belongsTo(RuteDetail::class, 'rute_detail_id'); }
    public function lokasiMangkal() { return $this->belongsTo(LokasiMangkal::class); }
    public function sales()         { return $this->hasMany(Sale::class); }

    public function getDurasiAttribute(): ?string {
        if (!$this->waktu_selesai) return null;
        $diff = $this->waktu_mulai->diff($this->waktu_selesai);
        return sprintf('%02d:%02d', $diff->h + ($diff->days * 24), $diff->i);
    }
    public function getTotalPenjualanAttribute(): float {
        return $this->sales()->where('status','valid')->sum('total');
    }
}
