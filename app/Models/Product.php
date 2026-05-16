<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
//use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->width(100);
        $this->addMediaConversion('small')->width(480);
        $this->addMediaConversion('large')->width(1200);
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function variationTypes()
    {
        return $this->hasMany(VariationType::class);
    }

   public function variations(): HasMany
   {
       return $this->hasMany(ProductVariation::class , 'product_id');

   }
}
