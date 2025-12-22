<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'program_studi';

    // Kolom yang boleh diisi massal
    protected $fillable = [
        'fakultas_id',
        'kode',
        'nama',
        'jenjang',
        'is_active',
    ];

    // Casting tipe data
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke Fakultas
     * Setiap program studi termasuk dalam satu fakultas
     */
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'fakultas_id');
    }

    /**
     * Relasi ke User
     * Satu program studi bisa memiliki banyak user/mahasiswa
     */
    public function users()
    {
        return $this->hasMany(User::class, 'program_studi_id');
    }

    /**
     * Relasi khusus untuk mahasiswa
     * Mengambil hanya user dengan role 'mahasiswa'
     */
    public function mahasiswa()
    {
        return $this->hasMany(User::class, 'program_studi_id')->where('role', 'mahasiswa');
    }

    /**
     * Relasi ke PendaftaranSidang
     * Satu program studi bisa memiliki banyak pendaftaran sidang
     */
    public function pendaftaranSidangs()
    {
        return $this->hasMany(PendaftaranSidang::class, 'program_studi_id');
    }
}