<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Http\Requests\StoreIngredientsRequest;
use App\Http\Requests\UpdateIngredientsRequest;

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

    public function store(StoreIngredientsRequest $request)
    {
        try{
            $validated = $request->validated();
            $validated['slug'] = Str::slug($validate['name']);
            $ingredients = ingredient::create($validated);

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

    public function update(UpdateIngredientsRequest $request,$slug)
    {
        try{
            $validated = $request->validated();
            if(isset($validated['name'])){
                $valdated['slug'] = Str::slug($valdated['name']);
            }

            $ingredients = ingredients::where('slug',$slug)->first();
            $ingredients->update($valdated);

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
