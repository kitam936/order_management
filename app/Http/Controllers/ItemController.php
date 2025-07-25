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
    public function store(StoreItemRequest $request)
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
            'prode_code' => $request->prode_code,
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        //
    }
}
