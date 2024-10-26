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

    public function store(Request $request, $listId)
    {
        $item = new Item();
        $item->name = $request->input('name');
        $item->item_list_id = $listId;
        $item->save();

        return response()->json(['message' => 'Item created successfully'],
            201);
    }

    public function destroy($itemId)
    {
        $item = Item::find($itemId);
        if ($item) {
            $item->delete();

            return response()->json(['message' => 'Item deleted successfully'],
                200);
        } else {
            return response()->json(['error' => 'Item not found'], 404);
        }
    }
}
