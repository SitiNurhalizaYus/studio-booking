<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
    'name',
    'price',
    'description',
    'duration',
];

public function getDurationAttribute($value)
{
    return (int) $value;
}


}
