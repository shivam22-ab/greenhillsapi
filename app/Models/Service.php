<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'image',
        'description',
        'location',
        'time',
        'price',
        'category_id',
        'slug'
    ];
    //Defining relations
    public function category()
    {
        $this->hasMany(Category::class);
    }
}
