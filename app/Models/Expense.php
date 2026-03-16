<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    // NO OLVIDES AÑADIR 'category_id' AQUÍ:
    protected $fillable = ['description', 'amount', 'expense_date', 'is_essential', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}