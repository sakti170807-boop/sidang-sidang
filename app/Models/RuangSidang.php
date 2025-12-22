<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RuangSidang extends Model
{
    protected $table = 'ruang_sidang';
    
    protected $fillable = [
        'kode', 'nama', 'gedung', 'kapasitas', 'lokasi',
        'link_virtual', 'memiliki_proyektor', 'support_online', 'is_active'
    ];

    protected $casts = [
        'memiliki_proyektor' => 'boolean',
        'support_online' => 'boolean',
        'is_active' => 'boolean'
    ];

    public function jadwalSidang()
    {
        return $this->hasMany(JadwalSidang::class);
    }
}