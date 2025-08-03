<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Car;
use inertia\Inertia;
use App\Models\User;
use App\Models\Item;
use App\Models\OrderDetail;
use Carbon\Carbon;


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

            return redirect()->route('orders.index')->with(['message'=>'更新されました','status'=>'info']);

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

        // dd($order_h, $order_f, $order_total);

            return Inertia::render('Orders/Show', [
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


        // dd($order_h, $order_fs, $order_total);

            return Inertia::render('Orders/Edit', [
                'order_h' => $order_h,
                'order_fs' => $order_fs,
                'order_total' => $order_total,
                'items' => $items,
                'shops' => $shops,
                'order_statuses' => $order_statuses,
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

        // dd($order->id,$request->all(), $user_id, $staff_id);

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
                'items.*.pcs' => 'required|integer|min:0',
                'items.*.sales_price' => 'required|numeric|min:0',
                'items.*.work_fee' => 'nullable|numeric|min:0',
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
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

            }

            DB::commit();
            return redirect()->route('orders.index')->with(['message'=>'発注登録されました','status'=>'info']);
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
}
