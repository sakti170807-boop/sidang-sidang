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
        'status',
        'catatan'
    ];

    // Relasi ke Jadwal Sidang
    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_sidang_id');
    }

    // Relasi ke Dosen (User)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi ke Penilaian (ONE TO ONE)
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

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Check if this penguji has submitted penilaian
    public function hasPenilaian()
    {
        return $this->penilaian()->exists();
    }

    // Get badge color for status
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }

    // Get status label in Indonesian
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'confirmed' => 'Dikonfirmasi',
            'pending' => 'Menunggu',
            'rejected' => 'Ditolak',
            default => 'Tidak Diketahui'
        };
    }
}