<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\Translatable;
use App\Services\CurrencyConversion;

class Product extends Model
{
    use HasFactory;

    use SoftDeletes, Translatable;

    protected $fillable = [
        'name', 'code', 'price', 'category_id', 'description', 'image', 'hit', 'new', 'recommend', 'count', 'name_en',
        'description_en'
    ];

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeHit($query)
    {
        return $query->where('hit', 1);
    }
    public function scopeNew($query)
    {
        return $query->where('new', 1);
    }
    public function scopeRecommend($query)
    {
        return $query->where('recommend', 1);
    }


    public function setNewAttribute($value)
    {
        $this->attributes['new'] = $value === 'on' ? 1 : 0;
    }
    public function setHitAttribute($value)
    {
        $this->attributes['hit'] = $value === 'on' ? 1 : 0;
    }
    public function setRecommendAttribute($value)
    {
        $this->attributes['recommend'] = $value === 'on' ? 1 : 0;
    }
    public function isHit()
    {
        return $this->hit === 1;
    }
    public function isNew()
    {
        return $this->new === 1;
    }
    public function isRecommend()
    {
        return $this->recommend === 1;
    }

    public function skus()
    {
        return $this->hasMany(Sku::class);
    }
    //TODO: check table name and fields
    public function properties()
    {
        return $this->belongsToMany(Property::class, 'property_product')->withTimestamps();
    }

}
