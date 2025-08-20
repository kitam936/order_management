<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderSubtotal;
use App\Models\Scopes\Subtotal;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use App\Services\DecileService;


class AnalysisController extends Controller
{
    public function index0(Request $request)
    {

        $subQuery = OrderSubtotal::betweenDate($request->startDate, $request->endDate);


        if ($request->type === 'perDay')
        {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y%m%d") as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
                ->groupBy('date')
                ->selectRaw('date, sum(sub_total) as total')
                ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }

        if ($request->type === 'perMonth')
        {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y%m") as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
                ->groupBy('date')
                ->selectRaw('date, sum(sub_total) as total')
                ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }
        if ($request->type === 'perYear')
        {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y") as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
                ->groupBy('date')
                ->selectRaw('date, sum(sub_total) as total')
                ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }
        if ($request->type === 'perWeek')
        {
            $subQuery->groupBy('id')
                ->selectRaw('YEARWEEK(pitin_date, 1) as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
                ->groupBy('date')
                ->selectRaw('date, sum(sub_total) as total')
                ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }

        return response()->json([
            'data' => $data,
            'type' => $request->type,
            'labels' => $data->pluck('date'),
            'totals' => $data->pluck('total'),
            ],
            Response::HTTP_OK);

    }

    public function index(Request $request)
    {
        // ユーザー一覧
        $customers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->select('orders.user_id as id', 'users.name','car_categories.car_name')
            ->groupBy('orders.user_id', 'users.name', 'car_categories.car_name')
            ->orderBy('car_categories.id', 'asc')
            ->get();

        // 車種一覧
        $car_categories = DB::table('orders')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->select('car_categories.id','car_categories.car_name')
            ->distinct()
            ->get();

        // 日付範囲指定
        $subQuery = OrderSubtotal::betweenDate($request->startDate, $request->endDate);

        // ★ ユーザー絞り込み
        if (!empty($request->customer_id)) {
            $subQuery->where('user_id', $request->customer_id);
        }

        // ★ 車種絞り込み
        if (!empty($request->car_category_id)) {
            $subQuery->where('car_category_id', $request->car_category_id);
        }

        // 集計処理
        if ($request->type === 'perDay') {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y%m%d") as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(sub_total) as total')
            ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');

        }

        if ($request->type === 'perMonth') {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y%m") as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(sub_total) as total')
            ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }

        if ($request->type === 'perYear') {
            $subQuery->groupBy('id')
                ->selectRaw('DATE_FORMAT(pitin_date,"%Y") as date, sum(subtotal) as sub_total')
                ->groupBy('date');
            $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(sub_total) as total')
            ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');
        }

        if ($request->type === 'perWeek') {
            $subQuery->groupBy('id')
                ->selectRaw('YEARWEEK(pitin_date, 1) as date, sum(subtotal) as sub_total')
                ->groupBy('date');

            $data = DB::table($subQuery)
            ->groupBy('date')
            ->selectRaw('date, sum(sub_total) as total')
            ->get();

            $labels = $data->pluck('date');
            $totals = $data->pluck('total');

        }

        if ($request->type === 'decile') {
            list($data,$labels, $totals) = DecileService::decile($subQuery);
            \Log::info('Decile Data', [
                'data' => $data,
                'labels' => $labels,
                'totals' => $totals,
            ]);
        }



        return response()->json([
            'data' => $data,
            'type' => $request->type,
            // 'labels' => $data->pluck('date'),
            'labels' => $labels,
            // 'totals' => $data->pluck('total'),
            'totals' => $totals,
            'customers' => $customers,
            'car_categories' => $car_categories,
        ], Response::HTTP_OK);
    }
}
