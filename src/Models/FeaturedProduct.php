<?php

namespace VelocityArtisans\FeaturedProducts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';
    
    protected $table = 'featured_products';

    public $incrementing = false;

    public $timestamps = true;

    public $fillable = ['product_id'];
}
