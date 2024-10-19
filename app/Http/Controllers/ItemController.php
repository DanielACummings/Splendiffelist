<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemList;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function show($listId)
    {
        $listExists = ItemList::where('id', $listId)->first() != null;
        if ($listExists) {
            $items = Item::where('item_list_id', $listId)->get();
            return response()->json($items);
        } else {
            return response()->json(['error' => 'List not found'], 404);
        }
    }
}
