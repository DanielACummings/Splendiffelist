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

    public function show(int $id)
    {
        $itemList = ItemList::findOrFail($id);

        return response()->json($itemList);
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

    public function destroy(int $id)
    {
        $list = ItemList::findOrFail($id);
        $list->delete();

        return response()->json(['message' => 'List deleted successfully']);
    }
}
