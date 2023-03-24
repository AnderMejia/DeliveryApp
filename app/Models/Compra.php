<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;


    public function repartidor(){
        return $this->belongsTo(Repartidor::class);
    }
    public function detalle_compras(){
        return $this->hasMany(DetalleCompra::class);
    }    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function forma_pago(){
        return $this->belongsTo(FormaPago::class);
    }

}
