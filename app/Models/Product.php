<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'user_id',
        'name',
        'short_name',
        'description',
        'url',
        'email_cc',
        'image'
    ];

    public function setImageAttribute($re_image)
    {
        $this->attributes['image'] = 'images/product/'.$re_image;
    }
}
