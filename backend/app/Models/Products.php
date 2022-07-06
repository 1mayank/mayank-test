<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
       'product_id',
        'isbn13',
        'title',
        'publication_date',
        'authors',
        'category',
        'concept',
        'language',
        'language_version',
        'tool',
        'vendor',
        'prices',
        'cover_image',
        'created_at',
        'updated_at'
    ];
}
