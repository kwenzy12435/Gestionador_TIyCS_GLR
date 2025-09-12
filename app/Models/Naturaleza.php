<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Naturaleza extends Model
{
    use HasFactory;

    protected $table = 'naturalezas';
    
    protected $fillable = ['nombre'];
}