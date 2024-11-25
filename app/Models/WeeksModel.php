<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class WeeksModel extends Model
{
    use HasFactory;

    protected $table='weeks';

    public function salesProjection(): HasMany{
        return $this->hasMany(SalesProjection::class);
    }

}
