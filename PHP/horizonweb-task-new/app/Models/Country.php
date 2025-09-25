<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function languages()
    {
        return $this->belongsToMany(Language::class, 'country_language');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'country_category');
    }
}
