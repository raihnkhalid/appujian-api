<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Siswa extends Model
{
    use HasFactory;
    protected $table = 'siswa';
    protected $fillable = [
        'user_id',
        'namalengkap',
        'noabsen',
        'nis',
        'kelas_id'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function kelases()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
}
