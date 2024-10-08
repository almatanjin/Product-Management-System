<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'description'
    ];

    protected $guarded = [];

    public function products(){
        return $this->belongsTo(Product::class, "product_id");
    }
}
