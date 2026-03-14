<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    // Esto le da permiso a Laravel de escribir en estas columnas
   protected $fillable = ['description', 'amount', 'expense_date', 'is_essential'];
}