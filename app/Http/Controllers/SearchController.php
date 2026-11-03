<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:100']);
        $term = trim((string) $r->input('q'));
        $products = collect();

        if ($term !== '') {
            $like = '%' . str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $term) . '%';
            $products = Product::with('variants')
                ->where(fn ($q) => $q->where('name', 'like', $like)
                    ->orWhere('category', 'like', $like)
                    ->orWhere('description', 'like', $like))
                ->orderBy('name')
                ->get();
        }

        return view('search', ['term' => $term, 'products' => $products]);
    }
}
