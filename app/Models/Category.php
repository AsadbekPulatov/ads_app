<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
