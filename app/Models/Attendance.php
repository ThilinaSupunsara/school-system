<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // updateOrCreate() පාවිච්චි කරන නිසා guarded හිස් කරනවා
    protected $guarded = [];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
