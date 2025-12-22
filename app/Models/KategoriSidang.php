<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSidang extends Model
{
    protected $table = 'kategori_sidang';
    
    protected $fillable = ['nama', 'kode', 'deskripsi', 'dokumen_wajib', 'is_active'];

    protected $casts = [
        'dokumen_wajib' => 'array',
        'is_active' => 'boolean'
    ];

    public function pendaftaran()
    {
        return $this->hasMany(Pendaftaran::class);
    }
}