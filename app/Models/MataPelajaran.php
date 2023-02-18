<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'nama',
        'mulai',
        'selesai'
    ];

    public function jadwals()
    {
        return  $this->belongsToMany(Jadwal::class);
    }

    public static function boot() {
        parent::boot();

        static::deleting(function($matapelajaran) {
            $matapelajaran->jadwals()->detach();
        });
    }
}
