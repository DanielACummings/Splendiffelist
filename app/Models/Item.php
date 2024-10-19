<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Item extends Model
{
    use HasFactory;

    // Specify the fillable attributes for mass assignment
    protected $fillable = [
        'name',
        'item_list_id',
        'crossed_out',
    ];

    // Define the relationships if needed
    public function itemList()
    {
        return $this->belongsTo(ItemList::class);
    }

    public function uniqueValidationRules($itemListId)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('items')->where(function ($query) use ($itemListId) {
                    return $query->where('item_list_id', $itemListId);
                }),
            ],
        ];
    }
}
