<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal',
        'jam',
        'tempat',
        'pic_se',
        'keterangan',
        'perusahaan',
        'nama',
        'contact'
    ];

    // Accessor to calculate days until the specified date (tanggal)
    public function getDaysRemainingAttribute()
    {
        $today = Carbon::now();
        $tanggal = Carbon::parse($this->tanggal);
        // Calculate difference and return as an integer
        return (int) $today->diffInDays($tanggal, false);
    }
}
