<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use HasFactory;
    protected $fillable = ['grade_id', 'fee_category_id', 'amount'];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function feeCategory()
    {
        return $this->belongsTo(FeeCategory::class);
    }
}
