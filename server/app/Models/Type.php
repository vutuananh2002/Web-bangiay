<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class Type extends BaseModel
{
    protected $table = 'types';
    protected $fillable = [
        'type_code',
        'slug',
    ];

    /**
     * Get the sizes that belong to the type.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sizes() {
        return $this->belongsToMany(Size::class, 'size_type', 'type_id', 'size_id')->withTimestamps();
    }
}
