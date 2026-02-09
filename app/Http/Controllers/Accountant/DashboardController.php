<?php

namespace App\Http\Controllers\Accountant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
        public function index()
        {

        $pendingInvoicesCount = Invoice::where('status', 'pending')
                                    ->orWhere('status', 'partial')
                                    ->count();


        $totalDueAmount = Invoice::whereIn('status', ['pending', 'partial'])
                                ->sum(DB::raw('total_amount - paid_amount'));


        $totalOverdueAmount = Invoice::whereIn('status', ['pending', 'partial'])
                                    ->where('due_date', '<', Carbon::today())
                                    ->sum(DB::raw('total_amount - paid_amount'));


        $monthlyIncome = Payment::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount');


       
        return view('accountant.dashboard', compact(
            'pendingInvoicesCount',
            'totalDueAmount',
            'totalOverdueAmount',
            'monthlyIncome'
        ));
    }
}
