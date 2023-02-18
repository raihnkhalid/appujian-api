<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;
    protected $table = 'ruangan';
    protected $fillable = [
        'no_ruangan',
        'kode_ruangan',
        'kelas_id',
        'kapasitas'
    ];
    protected $with = 'kelas';

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
