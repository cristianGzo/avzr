<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;


    public function modelo(): BelongsTo{
        return $this->belongsTo(Modelo::class);
    }

    public function category() : BelongsTo{
        return $this->belongsTo(Category::class);
    }

    public function loan(): HasMany{
        return $this->hasMany(Loan::class);
    }
}
