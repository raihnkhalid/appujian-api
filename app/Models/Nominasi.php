<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nominasi extends Model
{
    use HasFactory;
    protected $table = 'nominasi';
    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'no_ujian'
    ];

    // protected $with = [
    //     'siswa',
    //     'kelas'
    // ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
