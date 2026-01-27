<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Products::orderBy("id", "desc")->paginate(10);
        return view("customer.products.index", compact("products"));
    }
}
