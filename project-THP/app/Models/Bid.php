<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'user_id',
        'job_title',
        'client_name',
        'price',
        'submission_date',
        'status',
        'processed',
    ];

    // العلاقة مع الوظائف
    public function formPost(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(FormPost::class, 'post_id');
    }


    // العلاقة مع المستخدم
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
