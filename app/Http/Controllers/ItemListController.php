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
}
