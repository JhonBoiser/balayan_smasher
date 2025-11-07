<?php

// app/Http/Controllers/Frontend/HomeController.php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['category', 'primaryImage'])
            ->where('is_featured', true)
            ->where('is_active', true)
            ->take(8)
            ->get();

        $categories = Category::where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('frontend.home', compact('featuredProducts', 'categories'));
    }
}
