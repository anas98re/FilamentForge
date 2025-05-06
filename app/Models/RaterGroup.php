<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RaterGroup extends Model
{
    protected $fillable = ['name'];

    public function raters()
    {
        return $this->hasMany(Rater::class);
    }
}
