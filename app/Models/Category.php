<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Event;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Category extends Model
{
    use CrudTrait;

    protected $fillable = [
        'name',
        'image',
    ];
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
