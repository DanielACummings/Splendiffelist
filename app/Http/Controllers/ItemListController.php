<?php

namespace App\Http\Controllers;

use App\Helpers\Database;
use App\Models\ItemList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemListController extends Controller
{
    public function index()
    {
        $itemLists = ItemList::where('user_id', Auth::id())->get();

        return response()->json($itemLists);
    }

    public function show(int $id)
    {
        $itemList = Database::getValidList($id);
        if (!$itemList) {
            return response()->json(['error' => 'List not found'], 404);
        }

        return response()->json($itemList);
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string',]);
        $itemList = ItemList::create([
            'name' => $request->name,
            'user_id' => Auth::id(),
        ]);

        return response()->json($itemList, 201);
    }

    public function update(Request $request, int $id)
    {
        $itemList = Database::getValidList($id);
        if (!$itemList) {
            return response()->json(['error' => 'List not found'], 404);
        }

        $itemList->update($request->all());

        return response()->json($itemList);
    }

    public function destroy(int $id)
    {
        $itemList = Database::getValidList($id);
        if (!$itemList) {
            return response()->json(['error' => 'List not found'], 404);
        }

        $itemList->delete();

        return response()->json(['message' => 'List deleted successfully']);
    }
}
