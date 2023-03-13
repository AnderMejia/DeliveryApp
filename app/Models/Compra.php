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

    public function proveedor(){
        return $this->belongsTo(Proveedor::class);
    }

    public function producto(){
        return $this->belongsTo(Producto::class);
    }

    public function usuario(){
        return $this->belongsTo(User::class);
    }

    public function forma_pago(){
        return $this->belongsTo(FormaPago::class);
    }

}
