<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allowance extends Model
{
    use HasFactory;
    protected $fillable = ['staff_id', 'name', 'amount'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
