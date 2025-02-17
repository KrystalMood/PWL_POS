<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function foodBeverage()
    {
        return view('products.category.food-beverage', [
            'title' => 'Food & Beverage Products',
            'products' => [] // Add your products data here
        ]);
    }

    public function beautyHealth()
    {
        return view('products.category.beauty-health', [
            'title' => 'Beauty & Health Products',
            'products' => [] // Add your products data here
        ]);
    }

    public function homeCare()
    {
        return view('products.category.home-care', [
            'title' => 'Home Care Products',
            'products' => [] // Add your products data here
        ]);
    }

    public function babyKid()
    {
        return view('products.category.baby-kid', [
            'title' => 'Baby & Kid Products',
            'products' => [] // Add your products data here
        ]);
    }
}
