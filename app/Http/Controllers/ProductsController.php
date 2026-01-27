<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Products;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function viewProduct($id)
    {
        $product = Products::with('category')->find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Product not found');
        }

        return view("customer.products.view", compact("product"));
    }

    // Add this method to your ProductsController
    public function getRelatedProducts($id)
    {
        $product = Products::find($id);

        if (!$product) {
            return response()->json(['products' => []]);
        }

        // Get products from same category, excluding current product
        $relatedProducts = Products::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->limit(4)
            ->get();

        return response()->json(['products' => $relatedProducts]);
    }
    public function index(Request $request)
    {
        // Get filter parameters from request
        $categoryFilters = $request->input('categories', []);
        if($request->get('category')) {
            $categoryFilters[] = $request->get('category');
        }
        $priceFilter = $request->input('price');
        $sortBy = $request->input('sort', 'default');
        $search = $request->input('search');

        // Start query
        $query = Products::with('category');

        // Apply category filters
        if (!empty($categoryFilters)) {
            $query->whereHas('category', function ($q) use ($categoryFilters) {
                $q->whereIn('slug', $categoryFilters);
            });
        }

        // Apply price filter
        if ($priceFilter) {
            switch ($priceFilter) {
                case '0-50':
                    $query->where('price', '<=', 50);
                    break;
                case '50-100':
                    $query->whereBetween('price', [50, 100]);
                    break;
                case '100-200':
                    $query->whereBetween('price', [100, 200]);
                    break;
                case '200+':
                    $query->where('price', '>', 200);
                    break;
            }
        }

        // Apply search
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        // Apply sorting
        switch ($sortBy) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'rating':
               
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Paginate results
        $products = $query->paginate(12)->withQueryString();

        // Get all categories for filter sidebar
        $categories = Categories::all();

        // Check if this is an AJAX request (for filtering)
        if ($request->ajax()) {
            $view = view('customer.products.partials.products_grid', compact('products'))->render();
            return response()->json([
                'html' => $view,
                'count' => $products->count()
            ]);
        }

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function search(Request $request)
    {
        $search = $request->input('q');

        $products = Products::where('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%')
            ->with('category')
            ->paginate(12);

        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'search' => $search
            ]);
        }

        return view('customer.products.index', compact('products'));
    }
}