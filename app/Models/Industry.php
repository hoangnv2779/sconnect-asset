<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $table    = 'industries';
    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];
}
