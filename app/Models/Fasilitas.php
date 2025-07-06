<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;


    protected $fillable = ['nama'];

    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'fasilitas_layanans');
    }
}
