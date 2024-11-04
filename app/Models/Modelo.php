<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;
    protected $table='model';

    public function brand(): BelongsTo{
        return $this->belongsTo(Brand::class);
    }

    public function device() : HasMany{
        return $this->hasMany(Device::class);
    }
}
