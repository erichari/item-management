<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;
        /**
     * モデルの日付カラムの保存形式
     *
     * @var string
     */
    protected $dateFormat = 'Y/m/d H:i:s';
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'reply_id',
        'status',
    ];

}
