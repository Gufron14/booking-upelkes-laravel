<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarRuang extends Model
{
    use HasFactory;

    protected $fillable = ['ruang_id', 'kamar_id', 'path', 'keterangan'];

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'ruang_id');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'kamar_id');
    }
}
