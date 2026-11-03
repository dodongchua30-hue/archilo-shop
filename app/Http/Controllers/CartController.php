<?php
namespace App\Http\Controllers;
use App\Models\Variant;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public static function lines()
    {
        $c = session('cart', []);
        return Variant::with('product')->whereIn('id', array_keys($c))->get()
            ->map(fn ($v) => ['v' => $v, 'qty' => $c[$v->id]]);
    }
    public static function total($items) { return $items->sum(fn ($i) => $i['qty'] * $i['v']->product->price); }

    public function show()
    {
        $items = self::lines();
        return view('cart', ['items' => $items, 'total' => self::total($items)]);
    }
    public function add(Request $r)
    {
        $r->validate(['variant_id' => 'required|exists:variants,id', 'qty' => 'nullable|integer|min:1|max:5']);
        $v = Variant::findOrFail($r->variant_id);
        $cart = session('cart', []);
        $qty = min(($cart[$v->id] ?? 0) + ($r->qty ?? 1), $v->stock);
        if ($qty < 1) return back()->withErrors('That size is out of stock.');
        $cart[$v->id] = $qty;
        session(['cart' => $cart]);
        return redirect()->route('cart');
    }
    public function remove($variant)
    {
        $cart = session('cart', []); unset($cart[$variant]); session(['cart' => $cart]);
        return back();
    }
}
