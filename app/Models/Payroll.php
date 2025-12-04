<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;
    // All fields are fillable
    protected $guarded = [];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function payments()
    {
        return $this->hasMany(PayrollPayment::class);
    }
}
