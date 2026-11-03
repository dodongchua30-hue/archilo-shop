<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $guarded = [];

    private static ?array $cache = null;

    public function product() { return $this->belongsTo(Product::class); }

    /** Is this product in the logged-in user's wishlist? (one query per page) */
    public static function has(int $productId): bool
    {
        if (! auth()->check()) {
            return false;
        }

        self::$cache ??= static::where('user_id', auth()->id())->pluck('product_id')->all();

        return in_array($productId, self::$cache);
    }
}
