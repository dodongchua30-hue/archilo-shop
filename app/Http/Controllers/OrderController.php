<?php
namespace App\Http\Controllers;
use App\Models\{Order, StockLog, Variant};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    const SLOTS = ['10:00 AM', '11:00 AM', '1:00 PM', '3:00 PM', '5:00 PM'];

    public function checkout()
    {
        $items = CartController::lines();
        if ($items->isEmpty()) return redirect()->route('cart');
        return view('checkout', ['items' => $items, 'total' => CartController::total($items), 'slots' => self::SLOTS]);
    }
    public function store(Request $r)
    {
        $d = $r->validate(['pickup_date' => 'required|date|after_or_equal:today', 'pickup_slot' => 'required|in:' . implode(',', self::SLOTS)]);
        $items = CartController::lines();
        abort_if($items->isEmpty(), 404);

        $order = DB::transaction(function () use ($items, $d) {
            $o = Order::create($d + ['user_id' => auth()->id(), 'code' => 'BS-' . random_int(10000, 99999)]);
            $total = 0;
            foreach ($items as $i) {
                $v = Variant::lockForUpdate()->find($i['v']->id);
                if ($v->stock < $i['qty']) {
                    throw ValidationException::withMessages(['cart' => "{$i['v']->product->name} ({$v->size}) is no longer available."]);
                }
                $v->decrement('stock', $i['qty']);
                StockLog::record($v, -$i['qty'], "Reserved order {$o->code}");
                $price = $i['v']->product->price; $total += $price * $i['qty'];
                $o->items()->create(['variant_id' => $v->id, 'qty' => $i['qty'], 'price' => $price]);
            }
            $o->update(['total' => $total]);
            return $o;
        });
        session()->forget('cart');
        return redirect()->route('order.show', $order);
    }
    public function index()
    {
        return view('orders', ['orders' => Order::where('user_id', auth()->id())->latest()->get()]);
    }
    public function cancel(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);
        if ($order->status !== 'reserved') {
            return back()->withErrors('Only reserved orders can be cancelled online. Please contact the store about orders that are ready for pickup.');
        }
        $order->cancelAndRestock("Order {$order->code} cancelled by customer");
        return redirect()->route('order.show', $order)->with('ok', 'Your reservation was cancelled and the items were returned to stock.');
    }
    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id() || in_array(auth()->user()->role, ['admin', 'staff']), 403);
        return view('order', ['order' => $order->load('items.variant.product')]);
    }
}
