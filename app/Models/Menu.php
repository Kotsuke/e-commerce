<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Menu extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'menu_text',
        'menu_icon',
        'menu_url',
        'menu_order',
        'status',
    ];
}
