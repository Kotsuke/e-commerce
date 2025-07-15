<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categories extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'product_categories';

    // Kolom yang boleh diisi (opsional, tapi direkomendasikan)
    protected $fillable = ['name', 'slug', 'description', 'image'];

    /**
     * Relasi: Satu kategori memiliki banyak produk
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'product_category_id');
    }
}
