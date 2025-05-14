<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'nama',
        'jenis_customer',
        'email',
        'contact',
        'aniversary',
        'media_sosial',
        'alamat',
        'kota',
        'last_kerjasama',
        'status'
    ];

    // Define relationship with Collaboration
    public function collaborations()
    {
        return $this->hasMany(Collaboration::class);
    }

    // Accessor for Last Kerjasama
    public function getLastKerjasamaAttribute()
    {
        return $this->collaborations()->latest('tanggal')->value('tanggal');
    }

    // Accessor for Status (Average Communication Rating)
    public function getStatusAttribute()
    {
        $collaborations = $this->collaborations;

        // Jika tidak ada kolaborasi, status dianggap "No data"
        if ($collaborations->isEmpty()) {
            return 'No data';
        }

        // Hitung rata-rata skor status dari semua kolaborasi
        $averageScore = $collaborations->map(function ($collaboration) {
            return $collaboration->status_score; // Mengambil nilai skor status dari kolaborasi
        })->average();

        // Tentukan status berdasarkan nilai rata-rata skor
        if ($averageScore > 3) {
            return 'Baik Sekali';
        } elseif ($averageScore >= 2 && $averageScore <= 3) {
            return 'Baik';
        } elseif ($averageScore >= 1 && $averageScore < 2) {
            return 'Jelek';
        } else {
            return 'Jelek Sekali';
        }
    }
}
