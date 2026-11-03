<?php
namespace App\Http\Controllers;
use App\Models\Product;

class ShopController extends Controller
{
    public function home()
    {
        return view('home', ['products' => Product::with('variants')->latest()->take(8)->get()]);
    }
    public function category($category)
    {
        $q = Product::with('variants')->where('category', $category);
        if ($size = request('size')) {
            $q->whereHas('variants', fn ($v) => $v->where('size', $size)->where('stock', '>', 0));
        }
        return view('category', ['category' => $category, 'products' => $q->get()]);
    }
    public function product(Product $product)
    {
        return view('product', ['product' => $product->load('variants')]);
    }
}
