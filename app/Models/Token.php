<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    protected $fillable = ['user_id', 'balance'];

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    protected $keyType = 'int';

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}