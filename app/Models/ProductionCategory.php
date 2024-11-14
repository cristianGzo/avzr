<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionCategory extends Model
{
    use HasFactory;

    public function salesProjection(): HasMany{
        return $this->hasMany(SalesProjection::class);
    }
}
