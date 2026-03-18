<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class checkOrder extends Model
{
    use HasFactory;

    protected $fillable = ['total_cases','trip_cases_1','trip_cases_2','trip_cases_3','trip_cases_4','trip_cases_5','trip_cases_6','trip_cases_7','trip_cases_8','trip_cases_9','trip_cases_10','short_cases_status','short_case_reason','image','order_id','remaining_cases','received_cases','manager_recepit','checked_by','payment_method','check_number','invoice_amount','delivery_date'];

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }
}
