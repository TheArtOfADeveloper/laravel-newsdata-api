<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Language
 *
 * Represents a language code (e.g. 'en', 'fr') stored in the database.
 * Each language can be linked to multiple countries through
 * the pivot table `country_language`.
 */
class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Only the 'code' field can be set via mass assignment,
     * for example when calling Language::create([...]).
     *
     * @var array<int, string>
     */
    protected $fillable = ['code'];

    /**
     * Relationship: a language can belong to many countries.
     *
     * Uses the pivot table 'country_language' to maintain
     * the many-to-many relation between languages and countries.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function countries()
    {
        return $this->belongsToMany(Country::class, 'country_language');
    }
}
