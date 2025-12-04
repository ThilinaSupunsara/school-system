<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollPayment extends Model
{
    protected $fillable = ['payroll_id', 'amount', 'payment_date', 'method'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
