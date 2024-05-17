<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $transactions = $user->transactions;
        return view('transactions.index', ['transactions' => $transactions, 'balance' => $user->balance]);
    }

    public function showDeposits(Request $request)
    {
        $user = Auth::user();
        $deposits = $user->transactions()->where('transaction_type', 'deposit')->get();

        return view('transactions.deposit', ['deposits' => $deposits]);
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $amount = $request->amount;

        $user->transactions()->create([
            'transaction_type' => 'deposit',
            'amount' => $amount,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $user->increment('balance', $amount);

        return redirect()->back()->with('success', 'Data Successfully Added');
    }

    public function showWithdrawals(Request $request)
    {
        $user = Auth::user();
        $withdrawals = $user->transactions()->where('transaction_type', 'withdrawal')->get();

        return view('transactions.withdrawal', ['withdrawals' => $withdrawals]);
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = Auth::user();
        $amount = $request->amount;
        $balance = $user->balance;

        if ($amount > $balance) {
            return redirect()->back()->with('error', 'Insufficient balance')->withInput();
        }

        $fee = $this->calculateFee($user, $amount);
        $totalAmount = $amount + $fee;

        if ($totalAmount > $balance) {
            return response()->json(['error' => 'Insufficient balance for withdrawal and fee'], 400);
        }

        $user->transactions()->create([
            'transaction_type' => 'withdrawal',
            'amount' => $amount,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $user->decrement('balance', $totalAmount);

        return redirect()->back()->with('success', 'Data Successfully Added');
    }
    
    private function calculateFee($user, $amount)
    {
        $fee = 0;

        if ($user->account_type == 'Individual') {
            $today = Carbon::today();
            $firstOfMonth = $today->copy()->firstOfMonth();
            $totalWithdrawalsThisMonth = $user->transactions()
                ->where('transaction_type', 'withdrawal')
                ->whereBetween('created_at', [$firstOfMonth, $today])
                ->sum('amount');

            $remainingFreeThisMonth = max(0, 5000 - $totalWithdrawalsThisMonth);
            $freeAmount = min($amount, 1000);
            $chargeableAmount = max(0, $amount - $remainingFreeThisMonth);
            $remainingChargeableAmount = max(0, $chargeableAmount - $freeAmount);
            
            if ($today->isFriday() || $remainingChargeableAmount == 0) {
                $fee = 0;
            } else {
                $fee = $remainingChargeableAmount * 0.015 / 100;
            }
        } elseif ($user->account_type == 'Business') {
            $totalWithdrawals = $user->transactions()->where('transaction_type', 'withdrawal')->sum('amount');
            $feeRate = ($totalWithdrawals > 50000) ? 0.015 : 0.025;
            $fee = $amount * $feeRate / 100;
        }

        return $fee;
    }

}

