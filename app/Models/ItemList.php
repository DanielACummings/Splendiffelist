<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ItemList extends Model
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'user_id',
        'crossed_out',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'crossed_out' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the ItemList.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The attributes that should be unique for each user.
     * 
     * @return array<string>
     */
    public static function uniqueValidationRules($userId)
    {
        return [
            'name' => 'required|string|max:255|unique:item_lists,name,NULL,id,user_id,' . $userId,
        ];
    }
}
