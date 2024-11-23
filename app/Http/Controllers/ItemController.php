<?php

namespace App\Http\Controllers;

use App\Helpers\Database;
use App\Models\Item;
use App\Models\ItemList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function show($listId)
    {
        $validList = Database::getValidList($listId);
        if (!$validList) {
            return response()->json(['error' => 'List not found'], 404);
        }

        $items = Item::where('item_list_id', $listId)->get();

        return response()->json($items);
    }

    public function store(Request $request, $listId)
    {
        $validList = Database::getValidList($listId);
        if (!$validList) {
            return response()->json(['error' => 'List not found'], 404);
        }

        $item = new Item();
        $item->name = $request->input('name');
        $item->item_list_id = $listId;
        $item->save();

        return response()
            ->json(['message' => 'Item created successfully'], 201);
    }

    public function update(Request $request, int $itemId)
    {
        $item = $this->getValidItem($itemId);
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 403);
        }

        $item->update($request->all());

        return response()->json($item);
    }

    public function destroy($itemId)
    {
        $item = $this->getValidItem($itemId);
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }

        $item->delete();

        return response()
            ->json(['message' => 'Item deleted successfully'], 200);
    }

    private function getValidItem($itemId) {
        $item = Item::with('itemList')->find($itemId);
        if ($item === null) {
            return null;
        }
        else if ($item->itemList->user_id !== Auth::id()) {
            return null;
        }

        return $item;
    }
}
