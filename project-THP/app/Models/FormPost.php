<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class FormPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'minimum_budget',
        'maximum_budget',
        'deadline',
        'category',
        'location',
        'attachments',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:s\Z',
        'deadline' => 'date',
    ];

    // علاقة: المنشور يخص مستخدم واحد
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // علاقة: المنشور يحتوي على عروض (bids)
    public function bids()
    {
        return $this->hasMany(Bid::class, 'post_id', 'post_id');
    }
}
