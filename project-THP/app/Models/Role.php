<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $primaryKey = 'role_id';  // المفتاح الأساسي اسمه role_id
    public $incrementing = true;        // المفتاح auto-increment
    protected $keyType = 'int';         // نوع المفتاح int وليس string

    protected $fillable = ['role_name'];

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
