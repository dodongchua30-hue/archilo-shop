<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $guarded = [];
    protected $casts = ['pickup_date' => 'date'];
    public function items() { return $this->hasMany(OrderItem::class); }
    public function user() { return $this->belongsTo(User::class); }

    /** Cancel the order and put its items back in stock (once only), logging each stock change. */
    public function cancelAndRestock(string $reason): void
    {
        DB::transaction(function () use ($reason) {
            $order = static::lockForUpdate()->find($this->id);
            if ($order->status === 'cancelled') {
                return;
            }
            foreach ($order->items as $i) {
                $v = Variant::lockForUpdate()->find($i->variant_id);
                $v->increment('stock', $i->qty);
                StockLog::record($v, $i->qty, $reason);
            }
            $order->update(['status' => 'cancelled']);
        });

        $this->refresh();
    }
}
