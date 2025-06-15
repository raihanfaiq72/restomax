<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
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

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required'
        ]);

        try{
            $category = Category::create([
                'name'  => $request->name,
                'slug'  => Str::slug($request->name)
            ]);

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

    public function update(Request $request,$slug)
    {
        $request->validate([
            'name'  => 'required',
            'slug'  => 'required'
        ]);

        try{
            $category = Category::where('slug',$slug)->first();
            $category->update([
                'name'  => $request->name,
                'slug'  => Str::slug($request->name)
            ]);

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
