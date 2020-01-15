<?php

namespace App;

use App\Field;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['email', 'name', 'state'];

    public function fields()
    {
    	return $this->hasMany(Field::class);
    }
}
