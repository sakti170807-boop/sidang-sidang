<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revisi extends Model
{
    protected $table = 'revisi';
    
    protected $fillable = [
        'jadwal_sidang_id', 'penguji_id', 'catatan_revisi',
        'status', 'file_revisi', 'tanggal_submit', 'tanggal_approve'
    ];

    protected $casts = [
        'tanggal_submit' => 'datetime',
        'tanggal_approve' => 'datetime'
    ];

    public function jadwalSidang()
    {
        return $this->belongsTo(JadwalSidang::class);
    }

    public function penguji()
    {
        return $this->belongsTo(Penguji::class);
    }
}