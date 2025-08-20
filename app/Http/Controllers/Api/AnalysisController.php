<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderSubtotal;
use App\Models\Scopes\Subtotal;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;


class AnalysisController extends Controller
{
    public function index(Request $request)
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
            return response()->json([
                'data' => $data,
                'type' => $request->type,
                'labels' => $labels,
                'totals' => $totals,],
                Response::HTTP_OK);

    }
}
