<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'tag',
        'icon',
    ];

    public function user(){ 
        //タグを保持するユーザーの取得
        return $this->belongsTo(User::class);
    }
}
