<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function index(Request $request)
    {
        $query = Products::with('category');

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id') && $request->category_id !== 'all') {
            $query->where('category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status')) {
            if ($request->status === 'in_stock') {
                $query->where('stock_quantity', '>', 0);
            } elseif ($request->status === 'out_of_stock') {
                $query->where('stock_quantity', '<=', 0);
            } elseif ($request->status === 'low_stock') {
                $query->where('stock_quantity', '<', 10)->where('stock_quantity', '>', 0);
            }
        }

        $products = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $categories = Categories::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Categories::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string',
            'is_featured' => 'boolean',
            'is_available' => 'boolean',
        ]);

        $data = $request->except('image', 'images');

        // Upload main image
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath;
        }

        // Upload additional images
        $additionalImages = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $additionalImages[] = $path;
            }
            $data['images'] = json_encode($additionalImages);
        }

        $data['slug'] = \Str::slug($request->name . '-' . time());
        $data['is_featured'] = $request->has('is_featured');
        $data['is_available'] = $request->has('is_available');

        Products::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Products::findOrFail($id);
        $categories = Categories::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|min:2',
            'description' => 'required|string|min:10|max:5000',
            'price' => 'required|decimal:0,2|min:0.01|max:999999.99',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0|max:99999',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'weight' => 'nullable|numeric|min:0|max:9999.99',
            'dimensions' => 'nullable|string|max:100',
            'is_available' => 'sometimes|in:0,1,true,false,on,off',
            'is_featured' => 'sometimes|in:0,1,true,false,on,off',
        ]);

        // Start with validated data
        $data = $validated;

        // Handle main image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Store new image and get path
            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = $imagePath; // Store the path
        } else {
            // Keep the old image if no new image uploaded
            $data['image'] = $product->image;
        }

        // Handle additional images upload
        if ($request->hasFile('images')) {
            // Delete old additional images if they exist
            if ($product->images) {
                $oldImages = json_decode($product->images, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImage) {
                        if (Storage::disk('public')->exists($oldImage)) {
                            Storage::disk('public')->delete($oldImage);
                        }
                    }
                }
            }

            // Store new additional images
            $additionalImages = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/gallery', 'public');
                $additionalImages[] = $path;
            }
            $data['images'] = json_encode($additionalImages); // Store as JSON
        } else {
            // Keep the old images if no new images uploaded
            $data['images'] = $product->images;
        }

        // Handle boolean fields
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_available'] = $request->boolean('is_available');

        // Update the product with all data
        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        // Check if product has orders
        if ($product->orderItems()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete product with existing orders.');
        }

        // Delete images
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        if ($product->images) {
            $images = json_decode($product->images, true);
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'action' => 'required|in:delete,update_status,update_category',
            'status' => 'required_if:action,update_status',
            'category_id' => 'required_if:action,update_category|exists:categories,id',
        ]);

        $productIds = $request->products;

        switch ($request->action) {
            case 'delete':
                Products::whereIn('id', $productIds)->delete();
                $message = 'Products deleted successfully.';
                break;

            case 'update_status':
                Products::whereIn('id', $productIds)
                    ->update(['is_available' => $request->status === 'available']);
                $message = 'Products status updated successfully.';
                break;

            case 'update_category':
                Products::whereIn('id', $productIds)
                    ->update(['category_id' => $request->category_id]);
                $message = 'Products category updated successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}