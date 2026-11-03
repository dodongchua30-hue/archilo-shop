<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    protected $guarded = [];

    public function variant() { return $this->belongsTo(Variant::class); }

    public function user() { return $this->belongsTo(User::class); }

    /** Record a stock change. Call this AFTER the variant's stock has been updated. */
    public static function record(Variant $variant, int $delta, string $reason): void
    {
        static::create([
            'variant_id' => $variant->id,
            'user_id' => auth()->id(),
            'delta' => $delta,
            'stock_after' => $variant->stock,
            'reason' => $reason,
        ]);
    }
}
