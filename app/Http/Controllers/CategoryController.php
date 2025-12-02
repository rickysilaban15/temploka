<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Template;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('templates')->get();
        return view('categories.index', compact('categories'));
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $templates = Template::where('category_id', $category->id)
            ->where('is_active', true)
            ->with('category')
            ->paginate(12);

        return view('categories.show', compact('category', 'templates'));
    }
}