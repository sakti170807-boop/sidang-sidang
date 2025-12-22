<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $table = 'pengumuman';
    
    protected $fillable = [
        'judul', 'isi', 'target_audience', 'program_studi_id',
        'is_active', 'tanggal_mulai', 'tanggal_selesai', 'created_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime'
    ];

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
