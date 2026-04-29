<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
