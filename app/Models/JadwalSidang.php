<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSidang extends Model
{
    use HasFactory;

    protected $table = 'jadwal_sidang';

    protected $guarded = ['id'];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_active' => 'boolean',
    ];

    // ==================== RELATIONSHIPS ====================

    public function ruangSidang()
    {
        return $this->belongsTo(RuangSidang::class, 'ruang_sidang_id');
    }

    public function kategoriSidang()
    {
        return $this->belongsTo(KategoriSidang::class, 'kategori_sidang_id');
    }

    public function pembimbing()
    {
        return $this->belongsTo(Pembimbing::class, 'pembimbing_id');
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_sidang_id');
    }

    // Relasi ke Penguji (ONE TO MANY)
    public function penguji()
    {
        return $this->hasMany(PengujiSidang::class, 'jadwal_sidang_id');
    }

    // Relasi ke Penilaian (ONE TO MANY)
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'jadwal_sidang_id');
    }

    // ==================== SCOPES ====================

    public function scopeTerjadwal($query)
    {
        return $query->where('status', 'terjadwal');
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    public function scopeAktif($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal_mulai', $date);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('tanggal_mulai', [$startDate, $endDate]);
    }

    // ==================== HELPER METHODS ====================

    /**
     * Check if all penguji have submitted their penilaian
     */
    public function isAllPengujiSubmitted()
    {
        $totalPenguji = $this->penguji()->count();
        $submittedPenilaian = $this->penguji()->whereHas('penilaian')->count();
        
        return $totalPenguji > 0 && $submittedPenilaian >= $totalPenguji;
    }

    /**
     * Get percentage of submitted penilaian
     */
    public function getPenilaianProgress()
    {
        $totalPenguji = $this->penguji()->count();
        if ($totalPenguji == 0) return 0;
        
        $submittedPenilaian = $this->penguji()->whereHas('penilaian')->count();
        
        return round(($submittedPenilaian / $totalPenguji) * 100);
    }

    /**
     * Get the number of penguji who have submitted penilaian
     */
    public function getSubmittedPenilaianCount()
    {
        return $this->penguji()->whereHas('penilaian')->count();
    }

    /**
     * Get the total number of penguji
     */
    public function getTotalPengujiCount()
    {
        return $this->penguji()->count();
    }

    /**
     * Get average nilai from all penilaian
     */
    public function getAverageNilai()
    {
        $penilaians = $this->penilaians;
        
        if ($penilaians->isEmpty()) {
            return null;
        }

        return round($penilaians->avg('nilai_akhir'), 2);
    }

    /**
     * Check if jadwal can be marked as completed
     */
    public function canBeCompleted()
    {
        return $this->isAllPengujiSubmitted() && $this->status != 'completed';
    }

    /**
     * Mark jadwal as completed
     */
    public function markAsCompleted()
    {
        if ($this->canBeCompleted()) {
            $this->update(['status' => 'completed']);
            return true;
        }
        return false;
    }

    // ==================== ACCESSORS ====================

    /**
     * Get status badge color
     */
    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'terjadwal', 'scheduled' => 'info',
            'ongoing' => 'warning',
            'completed', 'selesai' => 'success',
            'dibatalkan', 'cancelled' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'terjadwal', 'scheduled' => 'Terjadwal',
            'ongoing' => 'Sedang Berlangsung',
            'completed', 'selesai' => 'Selesai',
            'dibatalkan', 'cancelled' => 'Dibatalkan',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get formatted date range
     */
    public function getFormattedDateRangeAttribute()
    {
        if (!$this->tanggal_mulai) return '-';
        
        $start = $this->tanggal_mulai->format('d M Y, H:i');
        
        if ($this->tanggal_selesai) {
            $end = $this->tanggal_selesai->format('H:i');
            return "{$start} - {$end}";
        }
        
        return $start;
    }
}