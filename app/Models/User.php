<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','is_active','phone','avatar'];
    protected $hidden   = ['password','remember_token'];
    protected $casts    = ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_active' => 'boolean'];

    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isDriver(): bool { return $this->role === 'driver'; }

    public function ruteSebagaiOwner() { return $this->hasMany(Rute::class, 'owner_id'); }
    public function ruteSebagaiDriver() { return $this->hasMany(Rute::class, 'driver_id'); }
    public function kunjungan()         { return $this->hasMany(Kunjungan::class, 'driver_id'); }
    public function kunjunganAktif()    { return $this->hasOne(Kunjungan::class, 'driver_id')->where('status','aktif'); }
}
