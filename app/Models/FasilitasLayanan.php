<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FasilitasLayanan extends Model
{
    use HasFactory;

    protected $fillable = ['layanan_id', 'fasilitas_id'];
}
