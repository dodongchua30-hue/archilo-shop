<?php

namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Product, StockLog, Variant};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StaffController extends Controller
{
    const CATEGORIES = ['Jeans', 'Skirts', 'Dresses', 'Tops'];
    const SIZES = ['S', 'M', 'L', 'XL'];

    public function dashboard()
    {
        return view('staff.dashboard', [
            'todayOrders' => Order::with('user')
                ->whereDate('pickup_date', today())
                ->whereIn('status', ['reserved', 'ready'])
                ->orderBy('pickup_slot')->get(),
            'reserved' => Order::where('status', 'reserved')->count(),
            'ready' => Order::where('status', 'ready')->count(),
            'lowItems' => Variant::with('product')
                ->whereColumn('stock', '<=', 'low_stock_at')
                ->orderBy('stock')->limit(10)->get(),
            'lowCount' => Variant::whereColumn('stock', '<=', 'low_stock_at')->count(),
            'productCount' => Product::count(),
        ]);
    }

    public function products()
    {
        return view('staff.products', [
            'products' => Product::withSum('variants', 'stock')
                ->orderBy('category')->orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('staff.product-form', [
            'product' => new Product(),
            'categories' => self::CATEGORIES,
            'sizes' => self::SIZES,
        ]);
    }

    public function store(Request $r)
    {
        $d = $this->validated($r);
        $stock = $r->validate([
            'stock' => 'nullable|array',
            'stock.*' => 'nullable|integer|min:0|max:1000',
        ])['stock'] ?? [];

        $data = collect($d)->except('photo', 'colours')->all();
        if ($image = $this->saveImage($r, $d['name'])) {
            $data['image'] = $image;
        }

        $p = Product::create($data);
        $colours = collect(explode(',', (string) ($d['colours'] ?? '')))
            ->map(fn ($c) => mb_substr(trim($c), 0, 30))
            ->filter()
            ->unique(fn ($c) => mb_strtolower($c))
            ->take(8)
            ->values();
        if ($colours->isEmpty()) {
            $colours = collect(['Classic']);
        }

        foreach ($colours as $colour) {
            foreach (self::SIZES as $size) {
                $qty = (int) ($stock[$size] ?? 0);
                $v = $p->variants()->create([
                    'size' => $size,
                    'color' => $colour,
                    'stock' => $qty,
                ]);
                if ($qty > 0) {
                    StockLog::record($v, $qty, 'Initial stock (new product)');
                }
            }
        }

        return redirect()->route('staff.products')->with('ok', "{$p->name} was added.");
    }

    public function edit(Product $product)
    {
        return view('staff.product-form', [
            'product' => $product,
            'categories' => self::CATEGORIES,
            'sizes' => self::SIZES,
        ]);
    }

    public function update(Request $r, Product $product)
    {
        $d = $this->validated($r);
        $data = collect($d)->except('photo', 'colours')->all();
        if ($image = $this->saveImage($r, $d['name'])) {
            $data['image'] = $image;
        }

        $product->update($data);

        return redirect()->route('staff.products')->with('ok', "{$product->name} was updated.");
    }

    public function destroy(Product $product)
    {
        $used = OrderItem::whereIn('variant_id', $product->variants()->pluck('id'))->exists();
        if ($used) {
            return back()->withErrors("{$product->name} has been ordered, so it cannot be deleted. Set its stock to 0 in the Stock Checker instead.");
        }

        $product->delete();

        return redirect()->route('staff.products')->with('ok', 'Product deleted.');
    }

    public function addColour(Request $r, Product $product)
    {
        $d = $r->validate([
            'colour' => 'required|string|max:30',
            'stock' => 'nullable|array',
            'stock.*' => 'nullable|integer|min:0|max:1000',
        ]);
        $name = trim($d['colour']);

        if ($product->variants()->whereRaw('LOWER(color) = ?', [mb_strtolower($name)])->exists()) {
            return back()->withErrors("{$product->name} already has the colour {$name}.");
        }

        foreach (self::SIZES as $size) {
            $qty = (int) ($d['stock'][$size] ?? 0);
            $v = $product->variants()->create(['size' => $size, 'color' => $name, 'stock' => $qty]);
            if ($qty > 0) {
                StockLog::record($v, $qty, "Initial stock ({$name})");
            }
        }

        return back()->with('ok', "Colour {$name} was added.");
    }

    public function renameColour(Request $r, Product $product)
    {
        $d = $r->validate(['old' => 'required|string|max:30', 'colour' => 'required|string|max:30']);
        $new = trim($d['colour']);

        if (strcasecmp($new, $d['old']) !== 0
            && $product->variants()->whereRaw('LOWER(color) = ?', [mb_strtolower($new)])->exists()) {
            return back()->withErrors("{$product->name} already has the colour {$new}.");
        }

        $product->variants()->where('color', $d['old'])->update(['color' => $new]);

        return back()->with('ok', 'Colour updated.');
    }

    private function validated(Request $r): array
    {
        return $r->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|in:' . implode(',', self::CATEGORIES),
            'price' => 'required|numeric|min:0|max:99999',
            'description' => 'nullable|string|max:1000',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png,webp,avif|max:5120',
            'colours' => 'nullable|string|max:200',
        ]);
    }

    private function saveImage(Request $r, string $name): ?string
    {
        if (! $r->hasFile('photo')) {
            return null;
        }

        $file = $r->file('photo');
        $filename = Str::slug($name) . '-' . time() . '.' . $file->extension();
        $file->move(public_path('images'), $filename);

        return $filename;
    }
}
