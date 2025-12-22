<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    protected $table = 'pendaftaran_sidang';
    
    protected $fillable = [
        'nomor_pendaftaran', 'mahasiswa_id', 'kategori_sidang_id',
        'program_studi_id', 'judul', 'abstrak', 'status',
        'catatan_admin', 'tanggal_submit', 'tanggal_verifikasi_pembimbing',
        'tanggal_verifikasi_admin'
    ];

    protected $casts = [
        'tanggal_submit' => 'datetime',
        'tanggal_verifikasi_pembimbing' => 'datetime',
        'tanggal_verifikasi_admin' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->nomor_pendaftaran)) {
                $model->nomor_pendaftaran = 'REG-' . date('Ymd') . '-' . str_pad(
                    static::whereDate('created_at', today())->count() + 1, 
                    4, 
                    '0', 
                    STR_PAD_LEFT
                );
            }
        });
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function kategoriSidang()
    {
        return $this->belongsTo(KategoriSidang::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class);
    }

    /**
     * Relasi JadwalSidang
     * Pastikan foreign key sesuai database ('pendaftaran_sidang_id')
     */
    public function jadwal()
    {
        return $this->hasOne(JadwalSidang::class, 'pendaftaran_sidang_id');
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class);
    }
}
