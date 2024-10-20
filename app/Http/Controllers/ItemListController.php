<?php

namespace App\Http\Controllers;

use App\Models\ItemList;
use Illuminate\Http\Request;

class ItemListController extends Controller
{
    public function index()
    {
        $itemLists = ItemList::all();
        return response()->json($itemLists);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $itemList = ItemList::create([
            'name' => $request->name,
            'user_id' => auth()->id(),
        ]);
        return response()->json($itemList);
    }
}
