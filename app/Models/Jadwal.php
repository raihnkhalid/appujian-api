<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $fillable = [
        'hari',
        'tanggal',
        'kelas_id'
    ];

    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class);
    }

    public static function boot(){
        parent::boot();

        static::deleting(function($jadwal){
            $jadwal->mataPelajarans()->detach();
        });
    }
}
