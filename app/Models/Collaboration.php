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
        $score = ($this->kepatuhan_pembayaran * 0.25) +
            ($this->komitmen_kontrak * 0.25) +
            ($this->respon_komunikasi * 0.25) +
            ($this->pengambilan_keputusan * 0.25);

        if ($score > 3) {
            return 'Sangat Baik';
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
            4 => 'Tepat Waktu',
            3 => 'Terlambat 2 Minggu',
            2 => 'Terlambat > 2 Minggu',
            1 => 'Terlambat > 1 Bulan / Tidak Membayar',
        ];
        return $options[$this->kepatuhan_pembayaran] ?? 'Unknown';
    }

    public function getKomitmenKontrakTextAttribute()
    {
        $options = [
            4 => 'Taat Pada Kontrak',
            3 => 'Beberapa Perubahan Kecil',
            2 => 'Beberapa Perubahan Besar',
            1 => 'Perubahan Mendadak / Tidak Mengikuti Kontrak',
        ];
        return $options[$this->komitmen_kontrak] ?? 'Unknown';
    }

    public function getResponKomunikasiTextAttribute()
    {
        $options = [
            4 => 'Cepat',
            3 => 'Cukup Baik',
            2 => 'Lambat',
            1 => 'Sangat Lambat',
        ];
        return $options[$this->respon_komunikasi] ?? 'Unknown';
    }

    public function getPengambilanKeputusanTextAttribute()
    {
        $options = [
            4 => 'Cepat dan Tepat',
            3 => 'Cukup Cepat',
            2 => 'Lambat',
            1 => 'Sangat Lambat',
        ];
        return $options[$this->pengambilan_keputusan] ?? 'Unknown';
    }

    public function getStatusScoreAttribute()
    {
        switch ($this->status) {
            case 'Sangat Baik':
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
