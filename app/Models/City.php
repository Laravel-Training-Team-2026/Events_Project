<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use CrudTrait;

    protected $fillable = ['name'];

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'city', 'name');
    }
}
