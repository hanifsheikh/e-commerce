<?php

namespace Modules\Seller\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    public function fetchData()
    {
        $data = DB::table('dashboard_caches')->whereMonth(
            'created_at',
            Carbon::now()->format('m')
        )->get();
        if (count($data)) {
            return ['stats' => [
                'total_income' => [
                    'value' =>  $data[0]->sum_of_commission,
                    'increased_by' => ($data[0]->sum_of_commission - $data[0]->sum_of_commission_previous_month),

                ],
                'total_orders' => [
                    'total_orders_in_current_month' => $data[0]->total_orders_in_current_month,
                    'increased_by' => ($data[0]->total_orders_in_current_month - $data[0]->total_orders_in_previous_month),
                ]
            ]];
        }
        return ['stats' => [
            'total_income' => [
                'value' =>  0,
                'increased_by' => 0,

            ],
            'total_orders' => [
                'total_orders_in_current_month' => 0,
                'increased_by' => 0,
            ]
        ]];
    }
}
