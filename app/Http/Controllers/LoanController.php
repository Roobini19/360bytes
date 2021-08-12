<?php

namespace App\Http\Controllers;

use App\Loan;
use App\LoanDue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    
    public function loanRequest(Request $request)
    {
        if(Auth::guard('api')->user() != null) {
            $request['status'] = 'submitted';
            $request['user_id'] = Auth::guard('api')->user()->id;
            $loan = Loan::create($request->all());

            return response()->json($loan);
        } else {
            return response()->json(['message' => 'Token Expired.Please login again!']);
        }        
    }

    public function getPaymentAmount(Request $request) {
        if(Auth::guard('api')->user() != null) {
            $getApprovedLoanDetails = Loan::where('status', 'approved')->where('user_id', Auth::guard('api')->user()->id)->first();
            $paymentDue = [];
            $calculatedPaymentAmount = $getApprovedLoanDetails->requiredfund / $getApprovedLoanDetails->repaymentterm;

            for($i=0; $i < $getApprovedLoanDetails->repaymentterm; $i++) {
                $isLoanExists = LoanDue::where('loan_id', $getApprovedLoanDetails->id)->count();

                if($isLoanExists <= $i) {
                    $loanDue = new LoanDue();
                    $loanDue->dueterm = $i+1;
                    $loanDue->loan_id = $getApprovedLoanDetails->id;
                    $loanDue->dueamount = number_format((float)$calculatedPaymentAmount, 2, '.', '');
                    $loanDue->balanceamount = $getApprovedLoanDetails->requiredfund;
                    $loanDue->user_id = Auth::guard('api')->user()->id;
                    $loanDue->save();
                }
            }

            $loanDues = LoanDue::where('loan_id', $getApprovedLoanDetails->id)->get();

            return response()->json(['amount' => number_format((float)$calculatedPaymentAmount, 2, '.', ''), 'payment_due' => $loanDues]);
        } else {
            return response()->json(['message' => 'Token Expired.Please login again!']);
        }        
    }

    public function loanPay(Request $request) {
        $loanDue = LoanDue::find($request->loandue_id);
        if($loanDue && $loanDue->status == 0) {
            if(round($request->dueamount) == round($loanDue->dueamount)) {
                $loanDue->status = 1;
                $loanDue->balanceamount -= $request->dueamount;
                $loanDue->save();

                LoanDue::where('status', 0)->where('loan_id', $loanDue->loan_id)->update(['balanceamount' => $loanDue->balanceamount]);

                $getBalanaceDues = LoanDue::where('status', 0)->where('loan_id', $loanDue->loan_id)->get();

                return response()->json(['message' => 'Due Paid!', 'getloandues' => $getBalanaceDues]);
            } else {
                return response()->json(['message' => 'Your amount is not suffient to paid due.']);
            }
        } else {
            return response()->json(['message' => 'Loan Due not Exist!'], 500);
        }
    }
}