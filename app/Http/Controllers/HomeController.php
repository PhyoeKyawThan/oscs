<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featured_products = Products::orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        return view('customer.home.index', compact('featured_products'));
    }

}
