<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';
    
    protected $fillable = [
        'pendaftaran_id',
        'jenis_dokumen',
        'file_path',
        'nama_file',
        'ukuran_file',
        'path',
        'mime_type',
        'ukuran',
    ];

    /**
     * Relasi ke Pendaftaran
     */
    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    /**
     * Accessor untuk mendapatkan URL file
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }

    /**
     * Accessor untuk format ukuran file (jika kolom ada)
     */
    public function getFormattedSizeAttribute()
    {
        if (!isset($this->attributes['ukuran_file'])) {
            return '-';
        }
        
        $bytes = $this->attributes['ukuran_file'];
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}