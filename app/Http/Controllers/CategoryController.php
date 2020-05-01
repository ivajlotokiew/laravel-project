<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
//        $this->middleware(['role:super-admin']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();

        return view('categories.index', ['categories' => $categories]);
    }

    /**
     * @param Category $category
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function category(Category $category)
    {
        return view('categories.category', compact('category'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxPostCategories(Request $request) {
        $categories = Category::all();
        if (!$categories) {
            return response()->json(['Error' => 'Something goes wrong!'], 404);
        }

        return response()->json($categories);
    }

}
