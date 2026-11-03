<?php

namespace App\Http\Controllers;

use App\Models\{Order, StockLog, Variant};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $collected = Order::where('status', 'collected');

        return view('owner.dashboard', [
            'salesToday' => (clone $collected)->whereDate('pickup_date', today())->sum('total'),
            'salesMonth' => (clone $collected)->whereYear('pickup_date', now()->year)->whereMonth('pickup_date', now()->month)->sum('total'),
            'salesAll' => (clone $collected)->sum('total'),
            'pendingValue' => Order::whereIn('status', ['reserved', 'ready'])->sum('total'),
            'counts' => Order::select('status', DB::raw('COUNT(*) as n'))->groupBy('status')->pluck('n', 'status'),
            'lowCount' => Variant::whereColumn('stock', '<=', 'low_stock_at')->count(),
            'outCount' => Variant::where('stock', 0)->count(),
            'top' => $this->productSales()->limit(5)->get(),
        ]);
    }

    public function reports(Request $r)
    {
        $r->validate(['from' => 'nullable|date', 'to' => 'nullable|date']);
        $from = $r->input('from') ?: now()->subDays(29)->toDateString();
        $to = $r->input('to') ?: now()->toDateString();
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }

        $byDay = DB::table('orders')
            ->where('status', 'collected')
            ->whereBetween('pickup_date', [$from, $to])
            ->selectRaw('pickup_date as day, COUNT(*) as orders, SUM(total) as revenue')
            ->groupBy('pickup_date')
            ->orderBy('pickup_date')
            ->get();

        $byProduct = $this->productSales($from, $to)->get();
        $byCategory = $byProduct->groupBy('category')->map(fn ($g) => [
            'qty' => $g->sum('qty'),
            'revenue' => $g->sum('revenue'),
        ]);

        return view('owner.reports', [
            'from' => $from,
            'to' => $to,
            'byDay' => $byDay,
            'byProduct' => $byProduct,
            'byCategory' => $byCategory,
            'totalRevenue' => $byDay->sum('revenue'),
            'totalOrders' => $byDay->sum('orders'),
            'totalItems' => $byProduct->sum('qty'),
        ]);
    }

    public function thresholds()
    {
        return view('owner.thresholds', [
            'variants' => Variant::with('product')->orderBy('product_id')->orderBy('id')->get(),
        ]);
    }

    public function setThreshold(Request $r, Variant $variant)
    {
        $variant->update($r->validate(['low_stock_at' => 'required|integer|min:0|max:500']));

        return back()->with('ok', 'Threshold saved.');
    }

    public function setAllThresholds(Request $r)
    {
        $d = $r->validate(['low_stock_at' => 'required|integer|min:0|max:500']);
        Variant::query()->update($d);

        return back()->with('ok', 'Threshold set for every item.');
    }

    public function history()
    {
        $q = StockLog::with('variant.product', 'user')->latest('id');
        if ($s = request('q')) {
            $q->whereHas('variant.product', fn ($p) => $p->where('name', 'like', "%$s%"));
        }

        return view('owner.history', ['logs' => $q->limit(200)->get()]);
    }

    /** Collected orders only, grouped by product. Optional date range on pickup date. */
    private function productSales(?string $from = null, ?string $to = null)
    {
        $q = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('variants', 'variants.id', '=', 'order_items.variant_id')
            ->join('products', 'products.id', '=', 'variants.product_id')
            ->where('orders.status', 'collected');

        if ($from && $to) {
            $q->whereBetween('orders.pickup_date', [$from, $to]);
        }

        return $q->select(
                'products.name',
                'products.category',
                DB::raw('SUM(order_items.qty) as qty'),
                DB::raw('SUM(order_items.qty * order_items.price) as revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.category')
            ->orderByDesc('qty');
    }
}
