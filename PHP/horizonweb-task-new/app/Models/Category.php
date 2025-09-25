<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Category
 *
 * Represents a news category (e.g. sports, health) stored in the database.
 * Each category can be linked to multiple countries through
 * the pivot table `country_category`.
 */
class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Only the 'name' field can be set via mass assignment,
     * for example when calling Category::create([...]).
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Relationship: a category can belong to many countries.
     *
     * Uses the pivot table 'country_category' to maintain
     * the many-to-many relation between categories and countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_category');
    }
}
