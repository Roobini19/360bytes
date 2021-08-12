<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoanDue extends Model
{
    protected $fillable = [
        'dueterm', 'dueamount', 'status', 'balanceamount'
    ];

    protected $hidden = ['updated_at'];
}
