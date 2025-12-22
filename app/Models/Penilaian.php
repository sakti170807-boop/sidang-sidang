<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian'; // Sesuai dengan database Anda
    
    protected $guarded = ['id'];

    protected $fillable = [
        'jadwal_sidang_id',
        'penguji_id',
        'nilai_presentasi',
        'nilai_materi',
        'nilai_diskusi',
        'nilai_akhir',
        'catatan',
    ];

    protected $casts = [
        'nilai_presentasi' => 'decimal:2',
        'nilai_materi' => 'decimal:2',
        'nilai_diskusi' => 'decimal:2',
        'nilai_akhir' => 'decimal:2',
    ];

    // Relasi ke Jadwal Sidang
    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class, 'jadwal_sidang_id');
    }

    // Relasi ke Penguji
    public function penguji()
    {
        return $this->belongsTo(PengujiSidang::class, 'penguji_id');
    }

    // Accessor - Predikat Nilai
    public function getPredikatAttribute()
    {
        if ($this->nilai_akhir >= 80) {
            return 'Sangat Memuaskan';
        } elseif ($this->nilai_akhir >= 70) {
            return 'Memuaskan';
        } elseif ($this->nilai_akhir >= 60) {
            return 'Cukup';
        } else {
            return 'Kurang';
        }
    }

    // Accessor - Status Kelulusan
    public function getStatusKelulusanAttribute()
    {
        return $this->nilai_akhir >= 60 ? 'Lulus' : 'Tidak Lulus';
    }

    // Helper method untuk mendapatkan warna badge berdasarkan predikat
    public function getPredikatColorAttribute()
    {
        if ($this->nilai_akhir >= 80) {
            return 'success'; // green
        } elseif ($this->nilai_akhir >= 70) {
            return 'info'; // blue
        } elseif ($this->nilai_akhir >= 60) {
            return 'warning'; // yellow
        } else {
            return 'danger'; // red
        }
    }
}