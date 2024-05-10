<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'ingredient',
        'ruby',
        'quantity',
    ];

    public function item(){ 
        //材料を保持するレシピの取得
        return $this->belongsTo(Ingredient::class);
    }
}
