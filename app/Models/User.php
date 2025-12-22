<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'nip', 'nim', 'nidn',
        'program_studi_id', 'no_telp', 'alamat', 'gelar_depan',
        'gelar_belakang', 'is_active'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class, 'mahasiswa_id');
    }

    // Relationship for penguji sidang
    public function pengujiSidang()
    {
        return $this->hasMany(PengujiSidang::class, 'dosen_id');
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDosen()
    {
        return $this->role === 'dosen';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    public function getFullNameAttribute()
    {
        $name = $this->name;

        if ($this->gelar_depan) {
            $name = $this->gelar_depan . ' ' . $name;
        }
        if ($this->gelar_belakang) {
            $name .= ', ' . $this->gelar_belakang;
        }

        return $name;
    }
}