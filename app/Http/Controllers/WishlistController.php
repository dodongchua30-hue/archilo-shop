<?php

namespace App\Http\Controllers;

use App\Models\{Product, Wishlist};

class WishlistController extends Controller
{
    public function index()
    {
        $ids = Wishlist::where('user_id', auth()->id())->latest('id')->pluck('product_id');

        return view('wishlist', [
            'products' => Product::with('variants')->whereIn('id', $ids)->get(),
        ]);
    }

    public function toggle(Product $product)
    {
        $row = Wishlist::where('user_id', auth()->id())->where('product_id', $product->id);

        if ($row->exists()) {
            $row->delete();
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
        }

        return back();
    }
}
