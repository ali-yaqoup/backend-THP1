<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FormPost extends Model
{
    use HasFactory;

    protected $primaryKey = 'post_id';
    protected $table = 'form_posts';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'minimum_budget',
        'maximum_budget',
        'deadline',
        'category',
        'location',
        'attachments'
        , 'status'

    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function bids(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Bid::class, 'post_id', 'post_id');
    }


}
