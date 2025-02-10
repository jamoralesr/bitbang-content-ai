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

    protected function casts(): array
    {
        return [
            'temas' => 'array',
            'categorias' => 'array',
        ];
    }

    // Transforma el atributo temas en un array JSON
    public function setTemasAttribute($value)
    {
        if (is_string($value)) {
            $array = array_filter(
                array_map('trim', 
                    explode(',', 
                        str_replace(["\r\n", "\n", "\r"], ',', $value)
                    )
                ),
                'strlen'
            );
            $this->attributes['temas'] = json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['temas'] = json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
    }
}
