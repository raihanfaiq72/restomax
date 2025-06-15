<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;

use App\Models\recipe;
use App\Models\product;
use App\Models\ingredient;

class RecipesController extends Controller
{
    public function index($slug)
    {
        $product_recipes = product::with('ingredients')->find($slug);

        return response()->json([
            'message'   => 'Show product with recipes',
            'product_recipes'=> $product_recipes
        ]);
    }

    public function store(StoreRecipeRequest $request, product $product)
    {
        $validatedData = $request->validated();
        
        $product->ingredients()->attach($validatedData['ingredient_id'], [
            'quantity_needed' => $validatedData['quantity_needed']
        ]);

        return response()->json([
            'message' => 'Ingredient added to recipe successfully'
        ], 201); 
    }

    public function update(UpdateRecipeRequest $request, product $product, ingredient $ingredient)
    {
        $validatedData = $request->validated();

        $product->ingredients()->updateExistingPivot($ingredient->id, [
            'quantity_needed' => $validatedData['quantity_needed']
        ]);

        return response()->json([
            'message' => 'Recipe quantity updated successfully'
        ]);
    }
    
    public function destroy(product $product, ingredient $ingredient)
    {
        // Gunakan 'detach' untuk menghapus baris dari tabel pivot
        $product->ingredients()->detach($ingredient->id);

        return response()->json([
            'message' => 'Ingredient removed from recipe successfully'
        ]);
    }
}
