<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GambarLayanan extends Model
{
    use HasFactory;

    
    protected $fillable = ['layanan_id', 'path', 'keterangan'];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }
}
