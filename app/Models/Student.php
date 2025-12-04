<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    // $fillable හෝ $guarded මෙතන දාන්න
    protected $guarded = [];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
    public function scholarships()
    {
        return $this->belongsToMany(Scholarship::class, 'student_scholarship');
    }
}
