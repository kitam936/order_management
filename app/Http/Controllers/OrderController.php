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
use App\Models\Seikyu;
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
            ->select(
                'seikyus.id as seikyu_id',
                'seikyus.seikyu_date',
                'customers.name as customer_name',
                'staffs.name as staff_name',
                'cars.car_info',
                'car_categories.car_name',
                'shops.shop_name',
                'seikyus.seikyu_kingaku',
                'pays.paid_date',
                'pays.paid_kingaku',
                'seikyus.seikyu_status',
                'seikyus.seikyu_info',
                'seikyu_statuses.seikyu_status_name',
            )
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('seikyus.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->orderBy('seikyus.id', 'desc')
            ->paginate(10)
            ->withQueryString();

            $total = DB::table('seikyus')
            ->join('users as customers', 'seikyus.user_id', '=', 'customers.id')
            ->join('users as staffs', 'seikyus.staff_id', '=', 'staffs.id')
            ->join('cars', 'seikyus.car_id', '=', 'cars.id')
            ->join('seikyu_statuses', 'seikyus.seikyu_status', '=', 'seikyu_statuses.id')
            ->leftJoin('car_categories', 'cars.car_category_id', '=', 'car_categories.id')
            ->join('shops', 'seikyus.shop_id', '=', 'shops.id')
            ->leftjoin('pays', 'seikyus.id', '=', 'pays.seikyu_id')
            ->where('car_category_id', 'like', '%' . $request->car_category_id . '%')
            ->where('seikyus.user_id',  'like', '%' . $request->customer_id . '%')
            ->where('customers.name', 'like', '%' . $request->search . '%')
            ->selectRaw(
                'sum(seikyus.seikyu_kingaku) as total_seikyu_kingaku,
                sum(pays.paid_kingaku) as total_paid_kingaku'
            )
            ->first();

        // dd($seikyus,  $total);

        return Inertia::render('Orders/SeikyuIndex', [
            'seikyus' => $seikyus,
            'customers' => $customers,
            'car_categories' => $car_categories,
            'total' => $total,
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


}
