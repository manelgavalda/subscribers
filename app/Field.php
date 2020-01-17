<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['title', 'type', 'value', 'subscriber_id'];

    public function getValueAttribute($value)
    {
    	return $this->type == 'boolean' ? boolval($value) : $value;
    }
}
