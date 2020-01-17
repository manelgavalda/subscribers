<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
	protected $with = ['fields'];

    protected $fillable = ['email', 'name', 'state'];

    public function fields()
    {
    	return $this->hasMany(Field::class);
    }
}
