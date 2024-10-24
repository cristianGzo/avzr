<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Mbctque extends Model
{

    //modelo para la tabla ShopFloor_Product
    protected $connection = 'sqlsrv_1';
    use HasFactory;

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'idEmpleado');
    }

    protected $table= 'SHOPFLOOR_PRODUCT_INSTANCE';

    /*protected $fillable = [
        'name',
        'email',
        'phone',
        'language'
    ];*/
}
