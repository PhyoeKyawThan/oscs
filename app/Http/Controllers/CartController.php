<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session()->get('cart', []);

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        // Return just the subtotal (not including shipping yet)
        $total = $subtotal;

        return view('customer.cart.index', compact(
            'cartItems',
            'subtotal',
            'total'
        ));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Products::findOrFail($request->product_id);

        // Check stock availability
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Only ' . $product->stock . ' items available.'
            ], 400);
        }

        $cart = session()->get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            // Update quantity if product already in cart
            $cart[$productId]['quantity'] += $request->quantity;

            // Check if new quantity exceeds stock
            if ($cart[$productId]['quantity'] > $product->stock) {
                $cart[$productId]['quantity'] = $product->stock;

                return response()->json([
                    'success' => false,
                    'message' => 'Maximum available quantity added to cart.'
                ]);
            }
        } else {
            // Add new item to cart
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $request->quantity,
                'image' => $product->product_image,
                'stock' => $product->stock,
                'category' => $product->category->name ?? 'Uncategorized'
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
            'cart_items' => $cart
        ]);
    }

    public function updateCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Products::findOrFail($request->product_id);
        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            // Check stock availability
            if ($request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $product->stock . ' items available in stock.'
                ], 400);
            }

            if ($request->quantity == 0) {
                // Remove item if quantity is 0
                unset($cart[$productId]);
            } else {
                // Update quantity
                $cart[$productId]['quantity'] = $request->quantity;
            }

            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
                'cart_items' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    public function removeFromCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $cart = session()->get('cart', []);
        $productId = $request->product_id;

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return response()->json([
                'success' => true,
                'message' => 'Product removed from cart!',
                'cart_count' => $this->getCartCount(),
                'cart_total' => $this->getCartTotal(),
                'cart_items' => $cart
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    public function clearCart()
    {
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!',
            'cart_count' => 0,
            'cart_total' => 0
        ]);
    }

    public function getCartData()
    {
        $cartItems = session()->get('cart', []);

        return response()->json([
            'cart_count' => $this->getCartCount(),
            'cart_total' => $this->getCartTotal(),
            'cart_items' => $cartItems
        ]);
    }

    private function getCartCount()
    {
        $cart = session()->get('cart', []);
        $count = 0;

        foreach ($cart as $item) {
            $count += $item['quantity'];
        }

        return $count;
    }

    private function getCartTotal()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}