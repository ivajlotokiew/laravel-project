<?php

namespace App\Http\Controllers;

use App\Category;
use App\Traits\UploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    use UploadTrait;

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

    public function ajaxPostUpdateCategory(Request $request) {
        $request->validate([
            'name' => 'required|min:2|max:255',
            'category_image' => $request['category_image'] !== 'undefined' ? 'mimes:jpeg,bmp,png' : '',
        ]);

        $category = Category::find($request['id']);

        if ($category->name !== $request['name']) {
            $category->name = $request['name'];
        }

        if ($request->has('category_image') && $request['category_image'] !== 'undefined') {
            // Get image file
            $image = $request->file('category_image');
            // Make a image name based on user name and current timestamp
            $name = Str::slug($category['name'] . '_' . time());
            // Define folder path
            $folder = '/images/';
            // Make a file path where image will be stored [ folder path + file name + file extension]
            $filePath = $folder . $name . '.' . $image->getClientOriginalExtension();
            // Upload image
            $this->uploadOne($image, $folder, 'public', $name);
            // Set user product image path in database to filePath
            $category->category_image = $filePath;
        }

        $category->save();

        return response()->json(['Success' => 'Category was successfully created!', 'category' => $category]);
    }

}
