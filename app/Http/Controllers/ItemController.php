<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\ItemCategory;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;


class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items=DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->select(
            'items.id',
            'items.item_category_id',
            'item_categories.item_category_name',
            'items.item_name',
            'items.item_price',
            'items.item_cost',
            'items.item_info',
            'items.created_at',
        )
        ->orderBy('items.item_category_id', 'asc')
        ->orderBy('items.id', 'asc')
        ->get();

        // dd($items);

        return Inertia::render('Items/Index',
            [
                'items' => $items,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories=DB::table('item_categories')
        ->select(
            'id',
            'item_category_name',
        )
        ->get();

        return Inertia::render('Items/Create', [
            'categories' => ItemCategory::all(),
            'old' => session()->getOldInput(),
            'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store0(StoreItemRequest $request)
    {
        // dd($request->file('item_image'));

        $folderName='items';
        if(!is_null($request->file('item_image'))){
            $fileName = uniqid(rand().'_');
            $extension = $request->file('item_image')->extension();
            $fileNameToStore = $fileName. '.' . $extension;
            $resizedImage = ImageManager::gd()->read($request->file('item_image'))
            // ->autoOrient()
            ->resize(400, 400,function($constraint){$constraint->aspectRatio();})->encode();

            // dd($fileNameToStore);
            Storage::put('items/' . $fileNameToStore, $resizedImage );
        }else{
            $fileNameToStore = '';
        };

        // dd($fileNameToStore);

        Item::create([
            'item_category_id' => $request->item_category_id,
            'prod_code' => $request->prod_code,
            'item_name' => $request->item_name,
            'item_price' => $request->item_price,
            'item_cost' => $request->item_cost,
            'item_info' => $request->item_info,
            'item_image' => $fileNameToStore,
        ]);

        return to_route('items.index')
        ->with([
            'message' => '商品が登録されました',
            'status' => 'info'])
        ;
    }

    public function store(StoreItemRequest $request)
    {
        $folderName = 'items';

        if (!is_null($request->file('item_image'))) {
            $fileName = uniqid(rand() . '_');
            $extension = $request->file('item_image')->extension();
            $fileNameToStore = $fileName . '.' . $extension;

            $filePath = $request->file('item_image')->getPathname();

            // ImageManagerインスタンスを作成（GD使用）
            $manager = ImageManager::withDriver(GdDriver::class); // ✅ 正しい
            $image = $manager->read($filePath);

            // Exif情報で向きを補正（GD + JPEGのみ）
            $exif = @exif_read_data($filePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = $image->rotate(180);
                        break;
                    case 6:
                        $image = $image->rotate(90);
                        break;
                    case 8:
                        $image = $image->rotate(-90);
                        break;
                }
            }

            // リサイズ（縦横比維持）
            $resizedImage = $image->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode(); // デフォルトで元のフォーマットでエンコード

            // 保存
            Storage::put($folderName . '/' . $fileNameToStore, $resizedImage);
        } else {
            $fileNameToStore = '';
        }

        Item::create([
            'item_category_id' => $request->item_category_id,
            'prod_code' => $request->prod_code,
            'item_name' => $request->item_name,
            'item_price' => $request->item_price,
            'item_cost' => $request->item_cost,
            'item_info' => $request->item_info,
            'item_image' => $fileNameToStore,
        ]);

        return to_route('items.index')->with([
            'message' => '商品が登録されました',
            'status' => 'info',
        ]);
    }



    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item=DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->where('items.id', $item->id)
        ->select(
            'items.id',
            'items.item_category_id',
            'item_categories.item_category_name',
            'items.prod_code',
            'items.item_name',
            'items.item_price',
            'items.item_cost',
            'items.item_info',
            'items.item_image',
            'items.created_at',
        )
        ->orderBy('items.item_category_id', 'asc')
        ->orderBy('items.id', 'asc')
        ->first();

        // dd($item);

        return Inertia::render('Items/Show'
            , [
                'item' => $item,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $item=DB::table('items')
        ->join('item_categories', 'items.item_category_id', '=', 'item_categories.id')
        ->where('items.id', $item->id)
        ->select(
            'items.id',
            'items.item_category_id',
            'item_categories.item_category_name',
            'items.prod_code',
            'items.item_name',
            'items.item_price',
            'items.item_cost',
            'items.item_info',
            'items.item_image',
            'items.created_at',
        )
        ->orderBy('items.item_category_id', 'asc')
        ->orderBy('items.id', 'asc')
        ->first();

        // dd($item);

        return Inertia::render('Items/Edit', [
            'item' => $item,
            'categories' => ItemCategory::all(),
            // 'old' => session()->getOldInput(),
            // 'errors' => session('errors') ? session('errors')->getBag('default')->getMessages() : [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        $folderName = 'items';

        $filePath = 'items/' . $item->item_image;

        // $is_exists = Storage::exists($filePath);

        // dd($filePath,$request->file('item_image'),$is_exists);

        if(!is_null($request->file('item_image')) && (Storage::exists($filePath))){
            Storage::delete($filePath);
            // Storage::disk('public')->delete($folderName . '/' . $filename);
        }

        if (!is_null($request->file('item_image'))) {
            $fileName = uniqid(rand() . '_');
            $extension = $request->file('item_image')->extension();
            $fileNameToStore = $fileName . '.' . $extension;

            $filePath = $request->file('item_image')->getPathname();

            // ImageManagerインスタンスを作成（GD使用）
            $manager = ImageManager::withDriver(GdDriver::class); // ✅ 正しい
            $image = $manager->read($filePath);

            // Exif情報で向きを補正（GD + JPEGのみ）
            $exif = @exif_read_data($filePath);
            if (!empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $image = $image->rotate(180);
                        break;
                    case 6:
                        $image = $image->rotate(90);
                        break;
                    case 8:
                        $image = $image->rotate(-90);
                        break;
                }
            }

            // リサイズ（縦横比維持）
            $resizedImage = $image->resize(400, 400, function ($constraint) {
                $constraint->aspectRatio();
            })->encode(); // デフォルトで元のフォーマットでエンコード

            // 保存
            Storage::put($folderName . '/' . $fileNameToStore, $resizedImage);
            // Storage::disk('public')->put($folderName . '/' . $fileNameToStore, $resizedImage);
        } else {
            $fileNameToStore = $item->item_image;
        }

        $item = Item::findOrFail($item->id);
        $item->item_category_id = $request->item_category_id;
        $item->prod_code = $request->prod_code;
        $item->item_name = $request->item_name;
        $item->item_price = $request->item_price;
        $item->item_cost = $request->item_cost;
        $item->item_info = $request->item_info;
        $item->item_image = $fileNameToStore;
        $item->save();


        return to_route('items.index')->with([
            'message' => '商品情報が更新されました',
            'status' => 'info',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return to_route('items.index')
        ->with([
            'message' => '商品が削除されました',
            'status' => 'alert'])
        ;
    }
}
