<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\View\View;

use App\Models\Product;

class HomeController extends Controller
{
    public function index(): View
    {
        //get posts
        $products = Product::latest()->paginate(8);

        //render view with posts
        return view('products.home', compact('products'));
    }
    public function show(string $id): View
    {
        //get post by ID
        $product = Product::findOrFail($id);

        //render view with post
        return view('home.show', compact('product'));
    }
}
