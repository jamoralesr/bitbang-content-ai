<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'entry_id',
        'url',
        'title',
        'resumen',
        'texto_descriptivo',
        'texto_descriptivo_sin_html',
        'regional',
        'temas',
        'categorias',
    ];

    protected $casts = [
        'categorias' => 'json',
    ];
}
