<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = ['title', 'type', 'value', 'subscriber_id'];
}
