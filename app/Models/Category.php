<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    use HasFactory;
    protected $fillable=[
        'name','description','slug'
    ];
    //Defining relations
    public function product(){
        $this->hasMany(Product::class);
    }
}
