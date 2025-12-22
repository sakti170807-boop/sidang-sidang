<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pembimbing extends Model
{
    protected $table = 'pembimbing';

    protected $fillable = [
        'pendaftaran_id', 
        'dosen_id', 
        'jenis',
        'status', 
        'catatan', 
        'tanggal_approve'
    ];

    protected $casts = [
        'tanggal_approve' => 'datetime',
        'jenis' => 'string',
        'status' => 'string',
    ];

    public $timestamps = true;

    // Relasi ke pendaftaran sidang
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    // Relasi ke dosen (User)
    public function dosen()
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }

    // Relasi shortcut ke mahasiswa (melalui pendaftaran)
    public function mahasiswa()
    {
        return $this->hasOneThrough(
            User::class,
            Pendaftaran::class,
            'id',            // Foreign key Pendaftaran di Pendaftaran
            'id',            // Foreign key User di User
            'pendaftaran_id',
            'mahasiswa_id'
        );
    }

    // Accessor untuk mempermudah tampilan tanggal
    public function getTanggalApproveFormattedAttribute()
    {
        return $this->tanggal_approve 
            ? Carbon::parse($this->tanggal_approve)->translatedFormat('d F Y')
            : '-';
    }
}
