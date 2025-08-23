<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Car;
use inertia\Inertia;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderDetail;
use App\Models\Seikyu;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Mpdf\Mpdf;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $customers = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        // ->where('users.role_id', 99) // Assuming role_id 3 is for customers
        // ->select('id', 'name', 'email', 'postcode', 'address', 'tel')
        ->select('orders.user_id as id', 'users.name')
        ->groupBy('orders.user_id', 'users.name')
        ->distinct()
        ->orderBy('orders.user_id', 'asc')
        ->get();

        $car_categories = DB::table('orders')
        ->join('cars', 'orders.car_id', '=', 'cars.id')
        ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
        ->distinct()
        ->select('car_category_id as id','car_name',    )
        ->get();

        $orders = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('orders.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'order_statuses.order_status_name',
                'orders.order_status',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status'
            )
            ->orderBy('orders.id', 'desc')
            // ->get();
            ->paginate(10)
            ->withQueryString();

            // dd($customers,$orders, $car_categories);

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
            'customers' => $customers,
            'car_categories' => $car_categories,
        ]);
    }

    public function my_order_index(Request $request)
    {

        $customers = DB::table('orders')
        ->join('users', 'orders.user_id', '=', 'users.id')
        ->where('orders.user_id', Auth::User()->id)
        // ->select('id', 'name', 'email', 'postcode', 'address', 'tel')
        ->select('orders.user_id as id', 'users.name')
        ->groupBy('orders.user_id', 'users.name')
        ->distinct()
        ->orderBy('orders.user_id', 'asc')
        ->get();

        $car_categories = DB::table('orders')
        ->join('cars', 'orders.car_id', '=', 'cars.id')
        ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
        ->where('cars.user_id', Auth::User()->id) // 自分の車のみ
        ->distinct()
        ->select('car_category_id as id','car_name',    )
        ->get();

        $orders = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('cars.user_id', Auth::User()->id) // 自分の車のみ
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            // ->where('orders.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'order_statuses.order_status_name',
                'orders.order_status',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status'
            )
            ->orderBy('orders.pitin_date', 'desc')
            // ->get();
            ->paginate(10)
            ->withQueryString();

            // dd($customers,$orders, $car_categories);

        return Inertia::render('MyOrders/MyOrder_Index', [
            'orders' => $orders,
            'customers' => $customers,
            'car_categories' => $car_categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // dd($request->customer_id);
        $customers = DB::table('users')
        ->join('cars', 'users.id', '=', 'cars.user_id')
        ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
        ->where('users.role_id', 99) // Assuming role_id 3 is for customers
        ->select('users.id as customer_id', 'name as customer_name', 'email', 'postcode', 'address', 'tel',
            'cars.id as car_id', 'car_categories.id as category_id','car_categories.car_name', 'cars.car_info')
        ->orderBy('category_id', 'asc')
        ->get();

        $staffs = DB::table('users')
        ->where('users.role_id', '<', 9) // Assuming role_id 3 is for customers
        ->select('id as staff_id', 'name as staff_name',)
        ->orderBy('staff_id', 'asc')
        ->get();

        $cars = DB::table('cars')
        ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
        // ->where('cars.user_id',  'like', '%' . $request->customer_id . '%')
        // ->where('cars.user_id', Auth::user()->id)
        ->select('cars.id as car_id', 'car_categories.car_name', 'cars.car_info',
            'cars.user_id', 'cars.car_category_id')
        ->orderBy('car_id', 'asc')
        ->get();

        $items = DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->select('items.id as id', 'item_categories.id as item_category_id','item_categories.item_category_name', 'items.item_name',
            'items.item_info', 'items.item_price')
        ->get();

        $shops = DB::table('shops')
        ->where('shops.id', '>',1100)
        ->select('id as shop_id', 'shop_name')
        ->get();

        // dd($cars);
        // dd($customers, $staffs, $items, $shops);

        return Inertia::render('Orders/Create', [
            'customers' => $customers,
            'staffs' => $staffs,
            'cars' => $cars,
            'items' => $items,
            'shops' => $shops,
        ]);

    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        $user_id = DB::table('cars')
            ->where('id', $request->car_id)
            ->value('user_id');

        $staff_id = Auth::user()->id;

        // dd($request->all(), $user_id, $staff_id);

        $validated = $request->validate([
            'pitin_date' => 'required|date',
            'car_id' => 'required|integer|exists:cars,id',
            'shop_id' => 'required|integer|exists:shops,id',
            'order_info' => 'nullable|string',
            'items' => 'required|array',
            'items.*.item_id' => 'integer|exists:items,id',
            'items.*.sales_price' => 'nullable|numeric',
            'items.*.pcs' => 'nullable|integer',
            'items.*.work_fee' => 'nullable|numeric',
            'items.*.detail_info' => 'nullable|string',
        ]);

        // dd($validated);



        DB::beginTransaction();

        try {
            // ① orders テーブルに保存
            $order = Order::create([
                'pitin_date' => $validated['pitin_date'],
                'user_id'    => $user_id,
                'staff_id'   => $staff_id,
                'car_id'     => $validated['car_id'],
                'shop_id'    => $validated['shop_id'],
                'order_info' => $validated['order_info'],
                'order_status' => 1, // 初期状態は未処理
            ]);

            // ② order_details テーブルに保存（20行のうち、item_id が入ってる行だけ）
            foreach ($validated['items'] as $item) {
                if (!empty($item['item_id'])) {
                    // dd($item['item_id']);
                    OrderDetail::create([
                        'order_id'    => $order->id,
                        'item_id'     => $item['item_id'],
                        'item_price'  => $item['sales_price'] ?? 0,
                        'item_pcs'    => $item['pcs'] ?? 0,
                        'work_fee'    => $item['work_fee'] ?? 0,
                        'detail_info' => $item['detail_info'] ?? '',
                        'detail_status' => 1, // 初期状態は未処理
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('orders.index')->with(['message'=>'発注登録されました','status'=>'info']);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order登録エラー', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors('登録エラー: ' . $e->getMessage());
        }

        // dd($e->getMessage());
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {

        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $order->id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->leftjoin('reports', 'order_details.id', '=', 'reports.detail_id')
            ->where('order_details.order_id', $order->id)
            ->groupBy(
                'order_details.id',
                'order_details.order_id',
                'order_details.item_id',
                'items.item_name',
                'items.item_info',
                'items.item_price',
                'order_details.item_pcs',
                'order_details.item_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.detail_status',
                'detail_statuses.detail_status_name',
                'reports.id',
            )
            ->selectRaw(
                'order_details.id as detail_id,
                order_details.order_id,
                order_details.id as order_detail_id,
                order_details.item_id,
                items.item_name,
                items.item_info,
                items.item_price,
                order_details.item_pcs,
                order_details.item_price as sales_price,
                order_details.work_fee,
                order_details.detail_info,
                order_details.detail_status,
                detail_statuses.detail_status_name,
                COUNT(reports.id) as report_cnt'
            )
            // ->select(
            //     'order_details.id as detail_id',
            //     'order_details.order_id',
            //     'order_details.id as order_detail_id',
            //     'order_details.item_id',
            //     'items.item_name',
            //     'items.item_info',
            //     'items.item_price',
            //     'order_details.item_pcs',
            //     'order_details.item_price as sales_price',
            //     'order_details.work_fee',
            //     'order_details.detail_info',
            //     'order_details.detail_status',
            //     'detail_statuses.detail_status_name',
            // )
            ->distinct()
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $order->id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        // dd($order_h, $order_fs, $order_total);

            return Inertia::render('Orders/Show', [
                'order_h' => $order_h,
                'order_fs' => $order_fs,
                'order_total' => $order_total,
            ]);
    }

    public function my_order_show(Order $order)
    {
        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $order->id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->leftjoin('reports', 'order_details.id', '=', 'reports.detail_id')
            ->where('order_details.order_id', $order->id)
            ->groupBy(
                'order_details.id',
                'order_details.order_id',
                'order_details.item_id',
                'items.item_name',
                'items.item_info',
                'items.item_price',
                'order_details.item_pcs',
                'order_details.item_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.detail_status',
                'detail_statuses.detail_status_name',
                'reports.id',
            )
            ->selectRaw(
                'order_details.id as detail_id,
                order_details.order_id,
                order_details.id as order_detail_id,
                order_details.item_id,
                items.item_name,
                items.item_info,
                items.item_price,
                order_details.item_pcs,
                order_details.item_price as sales_price,
                order_details.work_fee,
                order_details.detail_info,
                order_details.detail_status,
                detail_statuses.detail_status_name,
                COUNT(reports.id) as report_cnt'
            )
            // ->select(
            //     'order_details.id as detail_id',
            //     'order_details.order_id',
            //     'order_details.id as order_detail_id',
            //     'order_details.item_id',
            //     'items.item_name',
            //     'items.item_info',
            //     'items.item_price',
            //     'order_details.item_pcs',
            //     'order_details.item_price as sales_price',
            //     'order_details.work_fee',
            //     'order_details.detail_info',
            //     'order_details.detail_status',
            //     'detail_statuses.detail_status_name',
            // )
            ->distinct()
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $order->id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        // dd($order_h, $order_fs, $order_total);

            return Inertia::render('MyOrders/MyOrder_Show', [
                'order_h' => $order_h,
                'order_fs' => $order_fs,
                'order_total' => $order_total,
            ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        // dd($order);
        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $order->id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

            if ($order_h && $order_h->pitin_date) {
                $order_h->pitin_date = Carbon::parse($order_h->pitin_date)->format('Y-m-d');
            }

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->where('order_details.order_id', $order->id)
            ->select(
                'order_details.id as detail_id',
                'order_details.order_id',
                'order_details.id as order_detail_id',
                'order_details.item_id',
                'items.item_name',
                'items.item_info',
                'items.item_price',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.detail_status',
                'detail_statuses.detail_status_name',
            )
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $order->id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        $items = DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->select('items.id as id', 'item_categories.id as item_category_id','item_categories.item_category_name', 'items.item_name',
            'items.item_info', 'items.item_price')
        ->get();

        $shops = DB::table('shops')
        ->where('shops.id', '>',1100)
        ->select('id as shop_id', 'shop_name')
        ->get();

        $order_statuses = DB::table('order_statuses')
            ->select('id as order_status', 'order_status_name')
            ->get();

        $detail_statuses = DB::table('detail_statuses')
            ->orderBy('id', 'asc')
            ->get();


        // dd($order_h, $order_fs, $order_total);

            return Inertia::render('Orders/Edit', [
                'order_h' => $order_h,
                'order_fs' => $order_fs,
                'order_total' => $order_total,
                'items' => $items,
                'shops' => $shops,
                'order_statuses' => $order_statuses,
                'detail_statuses' => $detail_statuses,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        $user_id = DB::table('cars')
            ->where('id', $request->car_id)
            ->value('user_id');

        $staff_id = Auth::user()->id;

        // dd($request->all());

        DB::beginTransaction();
        try {
            // バリデーション（必要に応じて調整）
            $validated = $request->validate([
                'pitin_date' => 'required',
                'shop_id'    => 'required|integer',
                'car_id'     => 'required|integer',
                'order_info' => 'nullable|string',
                'items'      => 'array',
                'items.*.item_id' => 'required|integer',
                'items.*.pcs' => 'required|integer',
                'items.*.sales_price' => 'required|numeric',
                'items.*.work_fee' => 'nullable|numeric',
                'items.*.detail_info' => 'nullable|string',
            ]);

            // ヘッダ更新
            DB::table('orders')
                ->where('id', $order->id)
                ->update([
                    'pitin_date' => Carbon::parse($request->pitin_date)->format('Y-m-d H:i:s'),
                    'shop_id'    => $request->shop_id,
                    'user_id'    => $user_id,
                    'staff_id'   => $staff_id,
                    'car_id'     => $request->car_id,
                    'order_info' => $request->order_info,
                    'order_status' => $request->order_status , // デフォルトは未処理
                    'updated_at' => now(),
                ]);

            // 旧明細を削除
            DB::table('order_details')->where('order_id', $order->id)->delete();

            // 新明細を挿入
            foreach ($request->items as $item) {
                DB::table('order_details')->insert([
                    'order_id'     => $order->id,
                    'item_id'      => $item['item_id'],
                    'item_pcs'          => $item['pcs'],
                    'item_price'  => $item['sales_price'],
                    'work_fee'     => $item['work_fee'] ?? 0,
                    'detail_info'  => $item['detail_info'] ?? '',
                    'detail_status' => $item['detail_status'] ?? 1, // 初期状態は未処理
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            DB::commit();
            return redirect()->route('orders.show', ['order' => $order->id])->with(['message'=>'更新されました','status'=>'info']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Order更新エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '更新に失敗しました']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return to_route('orders.index')->with(['message'=>'削除されました','status'=>'alert']);
    }

    public function detail_edit($id)
    {
        $detail_statuses = DB::table('detail_statuses')
        ->orderBy('id', 'asc')
        ->get();

        $detail = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->where('order_details.id', $id)
            ->select(
                'order_details.id as detail_id',
                'order_details.order_id',
                'order_details.item_id',
                'items.item_name',
                'items.item_info',
                'items.item_price',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.detail_status',
                'detail_statuses.detail_status_name'
            )
            ->first();

        // dd($detail, $detail_statuses);

        return Inertia::render('Orders/DetailEdit', [
            'detail' => $detail,
            'detail_statuses' => $detail_statuses,
        ]);
    }

    public function detail_update(Request $request, $id)
    {
        // dd($request->all());

        // dd($is_yet);

        $validated = $request->validate([
            'detail_info' => 'nullable|string',
            'detail_status' => 'required|integer|exists:detail_statuses,id',
        ]);

        DB::beginTransaction();
        try {
            DB::table('order_details')
                ->where('id', $request->detail_id) ->update([
                    'detail_info' => $validated['detail_info'] ?? '',
                    'detail_status' => $validated['detail_status'],
                    'updated_at' => now(),
                ]);

                $is_yet = DB::table('order_details')
                    ->where('order_id', $request->order_id)
                    ->where('detail_status', '<',9) // 未処理のステータス
                    ->exists();

                if (!$is_yet) {
                    // 全ての作業が完了した場合は、注文ステータスを完了に設定
                    // dd($request->order_id);
                    DB::table('orders')
                        ->where('id', $request->order_id)
                        ->update([
                            'order_status' => 7, // 完了ステータス
                            'updated_at' => now(),
                        ]);
                }else {
                    // まだ未処理の作業がある場合は、注文ステータスを未処理に設定
                    DB::table('orders')
                        ->where('id', $request->order_id)
                        ->update([
                            'order_status' => 5, // 未処理ステータス
                            'updated_at' => now(),
                        ]);
                }


            DB::commit();

            sleep(1); // データベースの更新が反映されるまで少し待つ




            return redirect()->route('orders.show', ['order' => $request->order_id])->with(['message'=>'作業詳細が更新されました','status'=>'info']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('OrderDetail更新エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '更新に失敗しました']);
        }

    }

    public function confirm($id)
    {
        // dd($order);
        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

            if ($order_h && $order_h->pitin_date) {
                $order_h->pitin_date = Carbon::parse($order_h->pitin_date)->format('Y-m-d');
            }

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->where('order_details.order_id', $id)
            ->select(
                'order_details.id as detail_id',
                'order_details.order_id',
                'order_details.id as order_detail_id',
                'order_details.item_id',
                'items.item_name',
                'items.item_info',
                'items.item_price',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.detail_status',
                'detail_statuses.detail_status_name',
            )
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        $items = DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->select('items.id as id', 'item_categories.id as item_category_id','item_categories.item_category_name', 'items.item_name',
            'items.item_info', 'items.item_price')
        ->get();

        $shops = DB::table('shops')
        ->where('shops.id', '>',1100)
        ->select('id as shop_id', 'shop_name')
        ->get();

        $order_statuses = DB::table('order_statuses')
            ->select('id as order_status', 'order_status_name')
            ->get();


        // dd($order_h, $order_fs, $order_total);

            return Inertia::render('Orders/Confirm', [
                'order_h' => $order_h,
                'order_fs' => $order_fs,
                'order_total' => $order_total,
                'items' => $items,
                'shops' => $shops,
                'order_statuses' => $order_statuses,
            ]);
    }

    public function seikyu_index(Request $request)
    {
        // dd($request->all());
        $customers = DB::table('seikyus')
        ->join('users', 'seikyus.user_id', '=', 'users.id')
        // ->where('users.role_id', 99) // Assuming role_id 3 is for customers
        // ->select('id', 'name', 'email', 'postcode', 'address', 'tel')
        ->select('seikyus.user_id as id', 'users.name')
        ->groupBy('seikyus.user_id', 'users.name')
        ->distinct()
        ->orderBy('seikyus.user_id', 'asc')
        ->get();

        $car_categories = DB::table('seikyus')
        ->join('cars', 'seikyus.car_id', '=', 'cars.id')
        ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
        ->distinct()
        ->select('car_category_id as id','car_name',    )
        ->get();

        $seikyus = DB::table('seikyus')
            ->join('users as customers', 'seikyus.user_id', '=', 'customers.id')
            ->join('users as staffs', 'seikyus.staff_id', '=', 'staffs.id')
            ->join('cars', 'seikyus.car_id', '=', 'cars.id')
            ->join('seikyu_statuses', 'seikyus.seikyu_status', '=', 'seikyu_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->leftjoin('pays', 'seikyus.id', '=', 'pays.seikyu_id')
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('seikyus.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('seikyus.seikyu_status', 'like', '%' . $request->seikyu_status . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->groupBy(
                'seikyus.id',
                'seikyus.seikyu_date',
                'customers.name',
                'staffs.name',
                'cars.car_info',
                'car_categories.car_name',
                'shops.shop_name',
                'seikyus.seikyu_kingaku',

            )
            ->selectRaw(
                'seikyus.id as seikyu_id,
                seikyus.seikyu_date,
                customers.name as customer_name,
                cars.car_info,
                car_categories.car_name,
                shops.shop_name,
                seikyus.seikyu_kingaku,
                sum(pays.paid_kingaku) as paid_kingaku'
            )
            // ->distinct()
            ->orderBy('seikyus.id', 'desc')
            ->paginate(10)
            ->withQueryString();

            $seikyu_total = DB::table('seikyus')
            ->join('users as customers', 'seikyus.user_id', '=', 'customers.id')
            ->join('users as staffs', 'seikyus.staff_id', '=', 'staffs.id')
            ->join('cars', 'seikyus.car_id', '=', 'cars.id')
            ->join('seikyu_statuses', 'seikyus.seikyu_status', '=', 'seikyu_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('seikyus.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('seikyus.seikyu_status', 'like', '%' . $request->seikyu_status . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->selectRaw(
                'sum(seikyus.seikyu_kingaku) as total_seikyu_kingaku'
            )
            ->first();

            $paid_total = DB::table('pays')
            ->join('seikyus', 'pays.seikyu_id', '=', 'seikyus.id')
            ->join('users as customers', 'seikyus.user_id', '=', 'customers.id')
            ->join('users as staffs', 'seikyus.staff_id', '=', 'staffs.id')
            ->join('cars', 'seikyus.car_id', '=', 'cars.id')
            ->join('seikyu_statuses', 'seikyus.seikyu_status', '=', 'seikyu_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('seikyus.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('seikyus.seikyu_status', 'like', '%' . $request->seikyu_status . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->selectRaw(
                'sum(pays.paid_kingaku) as total_paid_kingaku'
            )
            ->first();

            $statuses = DB::table('seikyu_statuses')
            ->select('id as seikyu_status', 'seikyu_status_name')
            ->get();

        // dd($seikyus,  $total);

        return Inertia::render('Seikyu/SeikyuIndex', [
            'seikyus' => $seikyus,
            'customers' => $customers,
            'car_categories' => $car_categories,
            'statuses' => $statuses,
            'seikyu_total' => $seikyu_total,
            'paid_total' => $paid_total,
        ]);
    }

    public function seikyu_store(Request $request)
    {
        $user_id = DB::table('cars')
            ->where('id', $request->car_id)
            ->value('user_id');

        $staff_id = Auth::user()->id;

        // dd($request->all(), $user_id, $staff_id);

        DB::beginTransaction();
        try {
            // ヘッダ更新
            DB::table('orders')
                ->where('id', $request->order_id)
                ->update([
                    'order_status' => 10 ,
                    'updated_at' => now(),
                ]);

            // 旧明細を削除
            DB::table('order_details')->where('order_id', $request->order_id)->delete();

            // 新明細を挿入
            foreach ($request->items as $item) {
                DB::table('order_details')->insert([
                    'order_id'     => $request->order_id,
                    'item_id'      => $item['item_id'],
                    'item_pcs'          => $item['pcs'],
                    'item_price'  => $item['sales_price'],
                    'work_fee'     => $item['work_fee'] ?? 0,
                    'detail_info'  => $item['detail_info'] ?? '',
                    'detail_status' => 9, // 初期状態は未処理
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            }

            // バリデーション（必要に応じて調整）
            // $validated = $request->validate([
            //     'order_id' => 'required|integer|exists:orders,id',
            //     'seikyu_date' => 'required',
            //     'shop_id'    => 'required|integer',
            //     'car_id'     => 'required|integer',
            //     'seikyur_info' => 'nullable|string',
            //     'seikyu_kingaku' => 'required|numeric|min:0',
            // ]);

            $seikyu = Seikyu::create([
                'seikyu_date' => $request->seikyu_date,
                'user_id'    => $user_id,
                'staff_id'   => $staff_id,
                'order_id' => $request->order_id,
                'car_id'     => $request->car_id,
                'shop_id'    => $request->shop_id,
                'seikyu_info' => $request->seikyu_info,
                'seikyu_kingaku' => $request->seikyu_kingaku,
                'seikyu_status' => 1, // 初期状態は未処理
            ]);

            DB::commit();
            return redirect()->route('seikyu.index')->with(['message'=>'請求が登録されました','status'=>'info']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('請求登録エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '登録に失敗しました']);
        }
    }

    public function seikyu_show($id)
    {
        $seikyu = DB::table('seikyus')
            ->join('seikyu_statuses', 'seikyus.seikyu_status', '=', 'seikyu_statuses.id')
            ->join('users', 'seikyus.user_id', '=', 'users.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->where('seikyus.id', $id)
            ->select(
                'seikyus.id as seikyu_id',
                // 'seikyus.seikyu_date',
                DB::raw("DATE_FORMAT(seikyus.seikyu_date, '%Y-%m-%d') as seikyu_date"), // ★ここでフォーマット
                'seikyus.user_id',
                'users.name as customer_name',
                'shops.shop_name',
                'seikyus.seikyu_kingaku',
                'seikyus.seikyu_info',
                'seikyus.seikyu_status',
                'seikyu_statuses.seikyu_status_name',
            )
            ->first();

        $pays = DB::table('pays')
        ->join('pay_methods', 'pays.pay_method', '=', 'pay_methods.id')
            ->where('seikyu_id', $id)
            ->select(
                'pays.id as pay_id',
                'pays.paid_date',
                DB::raw("DATE_FORMAT(pays.paid_date, '%Y-%m-%d') as paid_date"), // ★ここでフォーマット
                'pays.paid_kingaku',
                'pays.pay_method',
                'pay_methods.pay_method_name'
            )
            ->orderBy('pay_id', 'desc')
            ->get();

        // dd($seikyu, $pays);

        return Inertia::render('Seikyu/SeikyuShow', [
            'seikyu' => $seikyu,
            'pays' => $pays,
        ]);
    }

    public  function pay_create($id)
    {
        // dd($request->all());
        $seikyu = DB::table('seikyus')
            ->join('users', 'seikyus.user_id', '=', 'users.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->where('seikyus.id', $id)
            ->select(
                'seikyus.id as seikyu_id',
                // 'seikyus.seikyu_date',
                DB::raw("DATE_FORMAT(seikyus.seikyu_date, '%Y-%m-%d') as seikyu_date"), // ★ここでフォーマット
                'seikyus.user_id',
                'users.name as customer_name',
                'shops.shop_name',
                'seikyus.seikyu_kingaku',
                'seikyus.seikyu_info',
            )
            ->first();

        $pay_methods = DB::table('pay_methods')
            ->select('id', 'pay_method_name')
            ->get();

        // dd($seikyu, $pay_methods);

        return Inertia::render('Seikyu/PayCreate', [
            'seikyu' => $seikyu,
            'pay_methods' => $pay_methods,
        ]);
    }

    public function pay_store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'seikyu_id' => 'required|integer|exists:seikyus,id',
            'user_id' => 'required|integer|exists:users,id',
            'paid_date' => 'required|date',
            'pay_method' => 'required',
            'paid_kingaku' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 支払い情報を保存
            DB::table('pays')->insert([
                'seikyu_id' => $validated['seikyu_id'],
                'user_id' => $validated['user_id'],
                'paid_date' => Carbon::parse($validated['paid_date'])->format('Y-m-d H:i:s'),
                'pay_method' => $validated['pay_method'],
                'paid_kingaku' => $validated['paid_kingaku'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $seikyu = DB::table('seikyus')
                ->where('id', $validated['seikyu_id'])
                ->select('seikyu_kingaku', 'seikyu_status')
                ->first();

            $paid_kingaku = DB::table('pays')
                ->where('seikyu_id', $validated['seikyu_id'])
                ->sum('paid_kingaku');


            // dd($seikyu, $paid_kingaku);

            // 請求ステータスを更新
            if ($paid_kingaku >= $seikyu->seikyu_kingaku) {
                DB::table('seikyus')
                    ->where('id', $validated['seikyu_id'])
                    ->update([
                        'seikyu_status' => 9, // 支払い済みステータス
                        'updated_at' => now(),
                    ]);
            }else {
                DB::table('seikyus')
                    ->where('id', $validated['seikyu_id'])
                    ->update([
                        'seikyu_status' => 5, // 一部支払いステータス
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();
            return redirect()->route('seikyu.index')->with(['message'=>'支払いが登録されました','status'=>'info']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('支払い登録エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '登録に失敗しました']);
        }
    }

    public function pay_show($id)
    {
        $pay = DB::table('pays')
            ->join('pay_methods', 'pays.pay_method', '=', 'pay_methods.id')
            ->where('pays.id', $id)
            ->select(
                'pays.id as pay_id',
                // 'pays.paid_date',
                DB::raw("DATE_FORMAT(pays.paid_date, '%Y-%m-%d') as paid_date"), // ★ここでフォーマット
                'pays.paid_kingaku',
                'pays.pay_method',
                'pay_methods.pay_method_name',
            )
            ->first();

        // dd($pay);

        return Inertia::render('Seikyu/PayShow', [
            'pay' => $pay,
        ]);
    }

    public function pay_edit($id)
    {
        $pay = DB::table('pays')
            ->join('pay_methods', 'pays.pay_method', '=', 'pay_methods.id')
            ->join('users', 'pays.user_id', '=', 'users.id')
            ->where('pays.id', $id)
            ->select(
                'pays.id as pay_id',
                'pays.seikyu_id',
                'pays.user_id',
                'users.name as customer_name',
                'pays.paid_date',
                DB::raw("DATE_FORMAT(pays.paid_date, '%Y-%m-%d') as paid_date"), // ★ここでフォーマット
                'pays.paid_kingaku',
                'pays.pay_method',
                'pay_methods.pay_method_name',
            )
            ->first();

        $pay_methods = DB::table('pay_methods')
            ->select('id', 'pay_method_name')
            ->get();

        // dd($pay, $pay_methods);

        return Inertia::render('Seikyu/PayEdit', [
            'pay' => $pay,
            'pay_methods' => $pay_methods,
        ]);
    }

    public function pay_update(Request $request, $id)
    {
        // dd($request->all());

        $validated = $request->validate([
            'seikyu_id' => 'required|integer|exists:seikyus,id',
            'paid_date' => 'required|date',
            'pay_method' => 'required',
            'paid_kingaku' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 支払い情報を更新
            DB::table('pays')
                ->where('id', $id)
                ->update([
                    'paid_date' => Carbon::parse($validated['paid_date'])->format('Y-m-d H:i:s'),
                    'pay_method' => $validated['pay_method'],
                    'paid_kingaku' => $validated['paid_kingaku'],
                    'updated_at' => now(),
                ]);

            $seikyu = DB::table('seikyus')
            ->where('id', $validated['seikyu_id'])
            ->select('seikyu_kingaku', 'seikyu_status')
            ->first();

            $paid_kingaku = DB::table('pays')
            ->where('seikyu_id', $validated['seikyu_id'])
            ->sum('paid_kingaku');

            if ($paid_kingaku >= $seikyu->seikyu_kingaku) {
                DB::table('seikyus')
                    ->where('id', $validated['seikyu_id'])
                    ->update([
                        'seikyu_status' => 9, // 支払い済みステータス
                        'updated_at' => now(),
                    ]);
            }else {
                DB::table('seikyus')
                    ->where('id', $validated['seikyu_id'])
                    ->update([
                        'seikyu_status' => 5, // 一部支払いステータス
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();
            return redirect()->route('seikyu.index')->with(['message'=>'支払いが更新されました','status'=>'info']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('支払い更新エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '更新に失敗しました']);
        }
    }

    public function pay_destroy($id)
    {
        // dd($id);
        DB::beginTransaction();
        try {
            // 支払い情報を削除
            DB::table('pays')->where('id', $id)->delete();

            // 請求ステータスを更新
            $seikyu_id = DB::table('pays')->where('id', $id)->value('seikyu_id');
            $paid_kingaku = DB::table('pays')->where('seikyu_id', $seikyu_id)->sum('paid_kingaku');

            $seikyu = DB::table('seikyus')->where('id', $seikyu_id)->first();

            if ($paid_kingaku >= 0) {
                DB::table('seikyus')
                    ->where('id', $seikyu_id)
                    ->update([
                        'seikyu_status' => 5, // 一部支払いステータス
                        'updated_at' => now(),
                    ]);
            }else {
                DB::table('seikyus')
                    ->where('id', $seikyu_id)
                    ->update([
                        'seikyu_status' => 1, // 未収ステータス
                        'updated_at' => now(),
                    ]);
            }

            DB::commit();
            return redirect()->route('seikyu.index')->with(['message'=>'支払いが削除されました','status'=>'alert']);
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('支払い削除エラー', ['message' => $e->getMessage()]);
            return back()->withErrors(['error' => '削除に失敗しました']);
        }
    }

    public function invoice0($id)
    {
        // confirm() と同じデータ取得処理
        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->where('order_details.order_id', $id)
            ->select(
                'order_details.id as detail_id',
                'order_details.item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee'
            )
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        // PDFビューに渡す
        // $pdf = Pdf::loadView('pdf.invoice', [
        //     'order_h' => $order_h,
        //     'order_fs' => $order_fs,
        //     'order_total' => $order_total
        // ])->setPaper('A4');

        $pdf = Pdf::loadView('pdf.invoice', [
            'order_h' => $order_h,
            'order_fs' => $order_fs,
            'order_total' => $order_total
        ])->setPaper('A4')
        ->setOptions([
            'isRemoteEnabled' => true,
            'defaultFont' => 'ipag'
        ]);

        // return $pdf->download('invoice_'.$id.'.pdf');



        return $pdf->download('invoice_'.$id.'.pdf');
    }

    public function invoice($id)
    {
        // confirm() と同じデータ取得処理
        $order_h = DB::table('orders')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->join('users as staffs', 'orders.staff_id', '=', 'staffs.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('order_statuses', 'orders.order_status', '=', 'order_statuses.id')
            ->join('shops', 'orders.shop_id', '=', 'shops.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->where('orders.id', $id)
            ->select(
                'orders.id as order_id',
                'orders.car_id',
                'orders.shop_id',
                'orders.staff_id',
                'orders.order_info',
                'car_categories.car_name',
                'customers.id as customer_id',
                'customers.name as customer_name',
                'shops.shop_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'orders.pitin_date',
                'orders.order_status',
                'order_statuses.order_status_name',
            )
            ->first();

        $order_fs = DB::table('order_details')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('detail_statuses', 'order_details.detail_status', '=', 'detail_statuses.id')
            ->where('order_details.order_id', $id)
            ->select(
                'order_details.id as detail_id',
                'order_details.item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
            )
            ->get();

        $order_total = DB::table('order_details')
            ->where('order_id', $id)
            ->sum(DB::raw('item_price * item_pcs + work_fee'));

        $html = view('pdf.invoice', compact('order_h', 'order_fs', 'order_total'))->render();

        // mPDF設定
        // BladeでHTMLレンダリング
        $html = view('pdf.invoice', compact('order_h', 'order_fs', 'order_total'))->render();

        // mPDF設定
        $mpdf = new Mpdf([
            'mode' => 'ja',                        // 日本語対応
            'default_font' => 'ipaexg',            // デフォルトフォント
            'fontDir' => array_merge(
                [storage_path('fonts')],           // ここに ipaexg.ttf を置く
                (new \Mpdf\Config\ConfigVariables())->getDefaults()['fontDir']
            ),
            'fontdata' => array_merge(
                [
                    'ipaexg' => [
                        'R' => 'ipaexg.ttf',
                        'useOTL' => 0xFF,          // 日本語最適化
                        'useKashida' => 75
                    ]
                ],
                (new \Mpdf\Config\FontVariables())->getDefaults()['fontdata']
            )
        ]);

        $mpdf->WriteHTML($html);

        // PDF出力（ブラウザ表示・ダウンロード）
        return $mpdf->Output("invoice_{$id}.pdf", \Mpdf\Output\Destination::INLINE);
    }

    public function orderCSV_download_all()
    {
        $orders = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->select(
                'orders.shop_id',
                'cars.id as car_id',
                'car_categories.car_name',
                'customers.id',
                'customers.name as customer_name',
                'item_categories.item_category_name',
                'items.id as item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.workingtime',
            )
            ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id',
            'car_id',
            'car_name',
            'customer_id',
            'customer_name',
            'item_category_name',
            'item_id',
            'item_name',
            'item_pcs',
            'item_price as sales_price',
            'work_fee',
            'detail_info',
            'workingtime',
        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        // Status変更
        // $orders=Order::where('order_status',1)->get();
        // foreach ($orders as $order) {
        //     $order->status = 3;
        //     $order->save();
        // }

        return $response;
    }

    public function orderCSV_download(Request $request)
    {
        $orders = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->where('cars.car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('orders.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->select(
                'orders.shop_id',
                'cars.id as car_id',
                'car_categories.car_name',
                'customers.id',
                'customers.name as customer_name',
                'item_categories.item_category_name',
                'items.id as item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.workingtime',
            )
            ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id',
            'car_id',
            'car_name',
            'customer_id',
            'customer_name',
            'item_category_name',
            'item_id',
            'item_name',
            'item_pcs',
            'item_price as sales_price',
            'work_fee',
            'detail_info',
            'workingtime',

        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        // Status変更
        // $orders=Order::where('order_status',1)->get();
        // foreach ($orders as $order) {
        //     $order->status = 3;
        //     $order->save();
        // }

        return $response;
    }

    public function my_orderCSV_download_all()
    {
        $orders = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->where('orders.user_id', Auth::id())
            ->select(
                'orders.shop_id',
                'cars.id as car_id',
                'car_categories.car_name',
                'customers.id',
                'customers.name as customer_name',
                'item_categories.item_category_name',
                'items.id as item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.workingtime',
            )
            ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id',
            'car_id',
            'car_name',
            'customer_id',
            'customer_name',
            'item_category_name',
            'item_id',
            'item_name',
            'item_pcs',
            'item_price as sales_price',
            'work_fee',
            'detail_info',
            'workingtime',
        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        // Status変更
        // $orders=Order::where('order_status',1)->get();
        // foreach ($orders as $order) {
        //     $order->status = 3;
        //     $order->save();
        // }

        return $response;
    }


    public function my_orderCSV_download(Request $request)
    {
        $orders = DB::table('orders')
            ->join('order_details', 'orders.id', '=', 'order_details.order_id')
            ->join('items', 'order_details.item_id', '=', 'items.id')
            ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
            ->join('cars', 'orders.car_id', '=', 'cars.id')
            ->join('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('users as customers', 'orders.user_id', '=', 'customers.id')
            ->where('cars.car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('orders.user_id', Auth::id())
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->select(
                'orders.shop_id',
                'cars.id as car_id',
                'car_categories.car_name',
                'customers.id',
                'customers.name as customer_name',
                'item_categories.item_category_name',
                'items.id as item_id',
                'items.item_name',
                'order_details.item_pcs',
                'order_details.item_price as sales_price',
                'order_details.work_fee',
                'order_details.detail_info',
                'order_details.workingtime',
            )
            ->get();

        // dd($request->order,$orders[0]);
        $csvHeader = [
            'shop_id',
            'car_id',
            'car_name',
            'customer_id',
            'customer_name',
            'item_category_name',
            'item_id',
            'item_name',
            'item_pcs',
            'item_price as sales_price',
            'work_fee',
            'detail_info',
            'workingtime',

        ];

        $csvData = $orders->toArray();

        // dd($request,$orders,$csvHeader,$csvData);

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $csvHeader);

            foreach ($csvData as $row) {
                $row = (array)$row; // 必要に応じてオブジェクトを配列に変換
                mb_convert_variables('sjis-win', 'utf-8', $row);
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="orders.csv"');

        // Status変更
        // $orders=Order::where('order_status',1)->get();
        // foreach ($orders as $order) {
        //     $order->status = 3;
        //     $order->save();
        // }

        return $response;
    }


}
