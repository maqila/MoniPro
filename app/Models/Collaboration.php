<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'kode',
        'customer_id',
        'nama_proyek',
        'pic',
        'jabatan',
        'contact',
        'tanggal',
        'kepatuhan_pembayaran',
        'komitmen_kontrak',
        'respon_komunikasi',
        'pengambilan_keputusan',
        'status',
        'dokumen',
        'pic_se'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Method to calculate and return the status
    public function calculateStatus()
    {
        $score = ($this->kepatuhan_pembayaran * 0.5) +
            ($this->komitmen_kontrak * 0.2) +
            ($this->respon_komunikasi * 0.15) +
            ($this->pengambilan_keputusan * 0.15);

        if ($score > 3) {
            return 'Baik Sekali';
        } elseif ($score > 2) {
            return 'Baik';
        } elseif ($score > 1) {
            return 'Jelek';
        } else {
            return 'Jelek Sekali';
        }
    }

    // Set the status attribute automatically when saving
    protected static function booted()
    {
        static::saving(function ($collaboration) {
            $collaboration->status = $collaboration->calculateStatus();
        });
    }

    public function getKepatuhanPembayaranTextAttribute()
    {
        $options = [
            4 => 'Terlambat < 14 Hari / Tepat Waktu',
            3 => 'Terlambat 15 s/d 30 Hari',
            2 => 'Terlambat 30 s/d 60 Hari',
            1 => 'Terlambat > 60 Hari',
        ];
        return $options[$this->kepatuhan_pembayaran] ?? 'Unknown';
    }

    public function getKomitmenKontrakTextAttribute()
    {
        $options = [
            4 => '> 100M',
            3 => '5M - 100M',
            2 => '1M - 5M',
            1 => '< 1M',
        ];
        return $options[$this->komitmen_kontrak] ?? 'Unknown';
    }

    public function getResponKomunikasiTextAttribute()
    {
        $options = [
            4 => 'Cepat',
            3 => 'Cukup Cepat',
            2 => 'Lambat',
            1 => 'Lambat Sekali',
        ];
        return $options[$this->respon_komunikasi] ?? 'Unknown';
    }

    public function getPengambilanKeputusanTextAttribute()
    {
        $options = [
            4 => '< 14 Hari',
            3 => '14 s/d 30 Hari',
            2 => '30 s/d 45 Hari',
            1 => '> 90 Hari',
        ];
        return $options[$this->pengambilan_keputusan] ?? 'Unknown';
    }

    public function getStatusScoreAttribute()
    {
        switch ($this->status) {
            case 'Baik Sekali':
                return 4;
            case 'Baik':
                return 3;
            case 'Jelek':
                return 2;
            case 'Jelek Sekali':
                return 1;
            default:
                return 0; // Jika tidak ada status yang cocok, kembalikan 0
        }
    }
}
