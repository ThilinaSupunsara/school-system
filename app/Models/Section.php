<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;
    protected $fillable = ['grade_id', 'name','class_teacher_id']; // Mass assignment සඳහා

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function classTeacher()
    {
        // 'class_teacher_id' column එක 'staff' model එකට join කරනවා
        return $this->belongsTo(Staff::class, 'class_teacher_id');
    }
    public function attendances()
    {
        // Section එකකට Attendances ගොඩක් තියෙනවා, හැබැයි Students හරහා
        return $this->hasManyThrough(Attendance::class, Student::class);
    }
}
