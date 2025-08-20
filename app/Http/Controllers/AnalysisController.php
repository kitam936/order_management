<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Order;
use App\Models\OrderSubtotal;
use App\Models\Scopes\Subtotal;
use Illuminate\Support\Facades\DB;


class AnalysisController extends Controller
{
    public function index()
    {
        $start_date = '2025-05-01';
        $end_date = '2025-8-31';
        $subQuery = OrderSubtotal::betweenDate($start_date, $end_date)
            ->groupBy('id')
            ->selectRaw('id,sum(subtotal) as total,
            customer_name,
            item_id,
            prod_code,
            item_name,
            item_price,
            item_pcs,
            work_fee,
            subtotal,
            DATE_FORMAT(pitin_date,"%Y%m%d") as pitin_date,
            created_at');

        $data = DB::table($subQuery)
            ->groupBy('pitin_date')
            ->selectRaw('pitin_date, sum(total) as total')
            ->get();

            // dd($data);


            // dd( $period);
        return Inertia::render('Analysis', [
            'title' => '分析',
            'description' => '分析ページの説明',
            'data' => $data,
        ]);
    }

    public function test()
    {
      dd(OrderSubtotal::paginate(50));
    }
}
