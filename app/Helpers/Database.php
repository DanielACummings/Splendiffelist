<?php

namespace App\Helpers;

use App\Models\ItemList;
use Illuminate\Support\Facades\Auth;

class Database {
    public static function getValidList($id) {
        return ItemList::where('id', $id)
            ->where('user_id', Auth::id())->first();
    }
}
