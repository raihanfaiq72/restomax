<?php

namespace App\Http\Controllers\Ingredients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ingredients\IngredientsStoreRequest;
use App\Http\Requests\Ingredients\IngredientsUpdateRequest;
use Inertia\Inertia;
use App\Models\ingredient; 

class IngredientsController extends Controller
{
    public function index()
    {
        return Inertia::render('ingredient/index', [
            'ingredients' => ingredient::latest()->get(),
        ]); 
    }

    public function store(IngredientsStoreRequest $request)
    {
        ingredient::create($request->validated());
        return redirect()->back()->with('success','Bahan-bahan berhasil ditambahkan');
    }

    public function update(IngredientsUpdateRequest $request, ingredient $ingredient)
    {
        $ingredient->update($request->validated());
        return redirect()->back()->with('success','Bahan berhasil di edit');
    }

    public function destroy(ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->back()->with('success','Bahan berhasil dihapus');
    }
}