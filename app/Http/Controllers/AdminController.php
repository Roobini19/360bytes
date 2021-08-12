<?php

namespace App\Http\Controllers;

use App\Admin;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Config;
use Validator;
use App\Loan;
use Hash;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = Admin::where('email', $request->email)->first();
        if ($user) {
            $token = $user->createToken('Laravel Password Grant Client')->accessToken;
            $response = ['token' => $token];
            return response($response, 200);
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
    }

    public function profile() {
        if(Auth::guard('admin')->user() != null) {
            $user = Admin::where('id', Auth::guard('admin')->user()->id)->first();

            return response()->json($user);
        } else {
            return response()->json(['message' => 'Token Expired.Please login again!']);
        }      
    }

    public function loanList() {
        $requestLoanLists = Loan::where('status', 'submitted')->with('user')->get();

        return response()->json($requestLoanLists);
    }

    public function loanApprove(Request $request, $id) {

        $getLoan = Loan::where('id', $id)->where('status', 'submitted')->where('user_id', $request->user_id)->update(['status' => 'approved']);

        if($getLoan == 1) {
            return response()->json(['message' => 'Loan approved successfully!', 'status' => 'success']);
        } else {
            return response()->json(['status' => 'failure', 'message' => 'Try again later'], 500);
        }     
    }

    public function loanReject(Request $request, $id) {

        $getLoan = Loan::where('id', $id)->where('status', 'submitted')->where('user_id', $request->user_id)->first();
        $getLoan->status = 'rejected';
        $getLoan->reasonforrejection = $request->reason;
        $getLoan->save();

        return response()->json(['message' => 'Loan rejected.', 'status' => 'success']);      
    }
}