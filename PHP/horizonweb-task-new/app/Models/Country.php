<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
/**
 * Class Country
 *
 * Represents a country record in the database.
 * Each country can have many languages and many categories
 * through pivot tables (country_language, country_category).
 */

class Country extends Model
{
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'code'];
    /**
     * Relationship: a country may have multiple languages.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function languages()
    {
        return $this->belongsToMany(Language::class, 'country_language');
    }

     /**
     * Relationship: a country may have multiple categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'country_category');
    }
}
