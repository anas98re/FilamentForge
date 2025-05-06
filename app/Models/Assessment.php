<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    protected $fillable = ['user_id', 'survey_type', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function raters()
    {
        return $this->hasMany(Rater::class);
    }
}
