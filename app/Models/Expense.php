<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $guarded = [];

    // Staff කෙනෙක් නම් ඒ සම්බන්ධය
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'category_id');
    }
    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    // නම පෙන්වීමට Helper function එකක්
    public function getRecipientNameAttribute()
    {
        if ($this->recipient_type == 'staff' && $this->staff) {
            return $this->staff->user->name . ' (Staff)';
        }
        return $this->external_name . ' (External)';
    }
}
