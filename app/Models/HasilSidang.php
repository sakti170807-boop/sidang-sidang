<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilSidang extends Model
{
    protected $table = 'hasil_sidang';
    
    protected $fillable = [
        'jadwal_sidang_id', 'keputusan', 'nilai_akhir',
        'grade', 'catatan', 'nomor_berita_acara',
        'file_berita_acara', 'tanggal_keputusan'
    ];

    protected $casts = ['tanggal_keputusan' => 'datetime'];

    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class);
    }
}