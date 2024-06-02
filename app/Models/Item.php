<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'serving',
        'score',
        'memo',
        'image',
        'draft',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    protected static function boot(){
        parent::boot();

        static::addGlobalScope('my_item', function ($query) {
            $query->where('items.user_id', Auth::user()->id,);
        });

    }

    public function ingredients(){
        //itemsの保持する全材料
        return $this->hasMany(Ingredient::class);
    }

    public function processes(){
        //itemsの保持する全工程
        return $this->hasMany(Process::class);
    }

    public function tags(){
        //itemsの保持する全タグ
        return $this->belongsToMany(Tag::class, 'item_tags')
            ->withTimestamps();
    }

    
}
http://localhost/phpmyadmin/index.php?route=/sql&db=item_management&table=items&pos=0

