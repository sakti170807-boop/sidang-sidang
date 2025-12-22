<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendaftaranSidang extends Model
{
    protected $table = 'pendaftaran_sidang';

    protected $fillable = [
        'nomor_pendaftaran',
        'mahasiswa_id',
        'kategori_sidang_id',
        'program_studi_id',
        'judul',
        'abstrak',
        'status',
        'catatan_admin',
        'tanggal_submit',
        'tanggal_verifikasi_pembimbing',
        'tanggal_verifikasi_admin'
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Relasi ke Kategori Sidang
    public function kategoriSidang()
    {
        return $this->belongsTo(KategoriSidang::class, 'kategori_sidang_id');
    }

    // Relasi ke Program Studi
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'program_studi_id');
    }

    // **Relasi ke Pembimbing**
    public function pembimbings()
    {
        return $this->hasMany(Pembimbing::class, 'pendaftaran_id');
    }

    // Relasi ke Dokumen
    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'pendaftaran_id');
    }
}
