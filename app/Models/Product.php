<?php

namespace App\Models;

use App\Enums\ProductStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class ,'created_by');
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function scopeForVendor(Builder $query): Builder
    {
        return $query->where('created_by',auth()->user()->id);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status',ProductStatusEnum::Published);
    }

    public function scopeForWebsite(Builder $query){
        return $query->published();
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
