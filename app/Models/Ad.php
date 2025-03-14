<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [
        'title',
        'description',
        'image',
        'price',
        'user_id',
    ];
    public function regions()
    {
        return $this->belongsToMany(Region::class, 'ads_regions', 'ad_id', 'region_id', 'id', 'id')
            ->orderby('id', 'ASC');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ads_categories', 'ad_id', 'category_id', 'id', 'id')
            ->orderby('id', 'ASC');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'ads_tags', 'ad_id', 'tag_id', 'id', 'id')
            ->orderby('id', 'ASC');
    }
}
