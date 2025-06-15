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
        $validator = [
            'name'  => 'required',
            'slug'  => 'required'
        ];

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

    public function show($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'message'   => 'show category by id',
            'category'  => $category
        ]);
    }

    public function update(Request $request,$id)
    {
        $validator = [
            'name'  => 'required',
            'slug'  => 'required'
        ];
        try{
            $category = Category::where('id',$id)->first();
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

    public function destroy($id)
    {
        $category = Category::where('id',$id)->first();
        $category->delete();

        return response()->json([
            'message'   => 'Category delete successfully',
        ]);
    }
}
