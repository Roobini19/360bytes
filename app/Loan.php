<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'requiredfund', 'repaymentterm', 'status', 'user_id'
    ];

    protected $hidden = ['updated_at'];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
