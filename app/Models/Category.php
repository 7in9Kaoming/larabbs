<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name', 'description',
    ];

    public function topic()
    {
    $this->hasMany(Topic::class);
    }
}
