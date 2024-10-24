<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class loan extends Model
{
    use HasFactory;

    public function empleado() : BelongsTo {
        return $this->belongsTo(Empleados::class);
    }

    public function device() : BelongsTo{
        return $this->belongsTo(Device::class);   
    }

    public function devolucion() : BelongsTo{
        return $this->belongsTo (Devolucion::class);
    }
}
