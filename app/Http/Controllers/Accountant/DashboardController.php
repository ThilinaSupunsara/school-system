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
            // 1. ගෙවීමට ඇති මුළු Pending Invoices ගණන
        $pendingInvoicesCount = Invoice::where('status', 'pending')
                                    ->orWhere('status', 'partial')
                                    ->count();

        // 2. ගෙවිය යුතු මුළු මුදල (Pending + Partial වල Balance)
        $totalDueAmount = Invoice::whereIn('status', ['pending', 'partial'])
                                ->sum(DB::raw('total_amount - paid_amount'));

        // 3. නියමිත දිනට පසුව (Overdue) ගෙවා නැති මුදල
        $totalOverdueAmount = Invoice::whereIn('status', ['pending', 'partial'])
                                    ->where('due_date', '<', Carbon::today())
                                    ->sum(DB::raw('total_amount - paid_amount'));

        // 4. මේ මාසයේ ලැබුණු ආදායම (මේක Admin dashboard එකේත් තිබ්බා)
        $monthlyIncome = Payment::whereYear('payment_date', now()->year)
                                ->whereMonth('payment_date', now()->month)
                                ->sum('amount');


        // මේ දත්ත 4 view එකට pass කරනවා
        return view('accountant.dashboard', compact(
            'pendingInvoicesCount',
            'totalDueAmount',
            'totalOverdueAmount',
            'monthlyIncome'
        ));
    }
}
