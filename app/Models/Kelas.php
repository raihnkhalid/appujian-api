<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Kelas extends Model
{
    use HasFactory;
    protected $table = 'kelas';
    protected $fillable = [
        'namakelas',
        'jurusan',
        'tingkat'
    ];

    public function ruangan()
    {
        return $this->hasMany(Ruangan::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }

    public function nominasi()
    {
        return $this->hasMany(Nominasi::class);
    }
}
