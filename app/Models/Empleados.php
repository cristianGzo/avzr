<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    use HasFactory;

    protected $table= 'Employee';

    public function usuario() : HasOne
    {
        return $this->hasOne(Usuario::class);
    }

    public function loan(): HasMany{
        return $this->hasMany(Loan::class);
    }

    public function department() : BelongsTo{
        return $this->belongsTo(Department::class);
    }
}
