<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'message'   => 'Show all category',
            'category'  => Category::get()
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        try{
            $validated = $request->validated();
            $validated['slug'] = Str::slug($validated['name']);
            
            $category = category::create($validated);

            return response()->json([
                'message'   => 'category created successfully',
                'category'  => $category
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Store category failed',
                'category'  => $e
            ]);
        }
    }

    public function show($slug)
    {
        $category = Category::where('slug',$slug)->first();

        return response()->json([
            'message'   => 'show category by id',
            'category'  => $category
        ]);
    }

    public function update(UpdateCategoryRequest $request,$slug)
    {
        try{
            $validated = $request->validated();
            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }
            $category = Category::where('slug',$slug)->first();
            $category->update($validated);

            return response()->json([
                'message'   => 'Category update succesfully'
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Category failed update',
                'category'  => $e
            ]);
        }
    }

    public function destroy($slug)
    {
        $category = Category::where('slug',$slug)->first();
        $category->delete();

        return response()->json([
            'message'   => 'Category delete successfully',
        ]);
    }
}
