<?php
namespace App\Http\Controllers;
use App\Models\{Order, StockLog, Variant};
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function stock()
    {
        $q = Variant::with('product');
        if ($s = request('q')) $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%$s%"));
        if (request('status') === 'low') $q->whereColumn('stock', '<=', 'low_stock_at')->where('stock', '>', 0);
        if (request('status') === 'out') $q->where('stock', 0);
        return view('admin.stock', [
            'variants' => $q->orderBy('stock')->get(),
            'low' => Variant::whereColumn('stock', '<=', 'low_stock_at')->count(),
            'pending' => Order::where('status', 'reserved')->count(),
        ]);
    }
    public function adjust(Request $r, Variant $variant)
    {
        $d = $r->validate(['change' => 'required|integer|between:-500,500']);
        $new = max(0, $variant->stock + $d['change']);
        $delta = $new - $variant->stock;
        $variant->update(['stock' => $new]);
        if ($delta !== 0) StockLog::record($variant, $delta, $delta > 0 ? 'Stock in' : 'Stock out');
        return back();
    }
    public function orders() { return view('admin.orders', ['orders' => Order::with('user')->latest()->get()]); }
    public function status(Request $r, Order $order)
    {
        $s = $r->validate(['status' => 'required|in:reserved,ready,collected,cancelled'])['status'];
        if ($order->status === 'cancelled') return back()->withErrors('Cancelled orders cannot be changed.');
        if ($s === 'cancelled') { // return reserved items to stock
            $order->cancelAndRestock("Order {$order->code} cancelled");
            return back();
        }
        $order->update(['status' => $s]);
        return back();
    }
}
