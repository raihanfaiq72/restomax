<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

use App\Models\ingredient;

class IngredientsController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'   => 'Show all ingredients',
            'ingredient'=> ingredient::get()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'stock_quantity'=>'required',
            'unit'=>'required',
            'low_stock_threshold'=>'required',
        ]);

        try{
            $ingredients = ingredient::create([
                'name'  => $request->name,
                'stock_quantity'=> $request->stock_quantity,
                'unit'=>$request->unit,
                'low_stock_threshold'=>$request->low_stock_threshold,
                'slug'=>Str::slug($request->name)
            ]);

            return response()->json([
                'message'=>'Ingredients store successfully',
                'ingredient' => $ingredients
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Ingredients store failed',
                'ingredient' => $e
            ]);
        }
    }

    public function show($slug)
    {
        $ingredients = ingredient::where('slug',$slug)->first();
        return response()->json([
            'message'   => 'Show ingredients by slug',
            'ingredient'   => $ingredients
        ]);
    }

    public function update(Request $request,$slug)
    {
        $request->validate([
            'name'  => 'required',
            'stock_quantity'=>'required',
            'unit'=>'required',
            'low_stock_threshold'=>'required',
        ]);

        try{
            $ingredients = ingredient::where('slug',$slug)->first();
            $ingredients->update([
                'name'  => $request->name,
                'stock_quantity'=> $request->stock_quantity,
                'unit'=>$request->unit,
                'low_stock_threshold'=>$request->low_stock_threshold,
                'slug'=>Str::slug($request->name)
            ]);

            return response()->json([
                'message'=>'Ingredients update successfully',
                'ingredient' => $ingredients
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'=>'Ingredients update failed',
                'ingredient' => $e
            ]);
        }
    }

    public function destroy($slug)
    {
        $ingredients = Ingredient::where('slug',$slug)->first();
        $ingredients->delete();

        return response()->json([
            'message'   => 'Ingredients delete succesfully',
        ]);
    }
}
