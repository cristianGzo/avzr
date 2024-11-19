<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesProjection extends Model
{
    use HasFactory;
    protected $table = 'salesProjection';
    public function productionCategory(): BelongsTo{
        return $this->belongsTo(ProductionCategory::class);
    }
}
