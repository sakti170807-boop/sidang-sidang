<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengujiSidang extends Model
{
    use HasFactory;

    protected $table = 'penguji_sidang';

    protected $fillable = [
        'jadwal_sidang_id',
        'dosen_id',
        'peran',
        // 'status' dihapus karena tidak ada di tabel
    ];

    // Relationships
    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_sidang_id');
    }

    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    public function penilaian()
    {
        return $this->hasOne(Penilaian::class, 'penguji_id');
    }

    // Scopes
    public function scopeByPeran($query, $peran)
    {
        return $query->where('peran', $peran);
    }

    public function scopeKetua($query)
    {
        return $query->where('peran', 'ketua');
    }

    public function scopeAnggota($query)
    {
        return $query->where('peran', 'anggota');
    }

    public function scopeSekretaris($query)
    {
        return $query->where('peran', 'sekretaris');
    }
}