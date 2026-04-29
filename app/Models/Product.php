<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;

class Product extends Model
{
    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
