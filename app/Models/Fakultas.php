<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fakultas extends Model
{
    protected $table = 'fakultas';
    
    protected $fillable = ['kode', 'nama', 'deskripsi', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function programStudi()
    {
        return $this->hasMany(ProgramStudi::class);
    }
}
