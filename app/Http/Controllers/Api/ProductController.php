<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

use App\Models\product;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'   => 'Show all products',
            'product'   => product::get()
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        try{
            $validated = $request->validated();
            $validated['slug'] = Str::slug($validated['name']);
            $product = product::create($validated);

            return response()->json([
                'message'   => 'Product store success',
                'product'   => $product
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Product store failed',
                'product'   => $e
            ]);
        }
    }

    public function show($slug)
    {
        $product = product::where('slug',$slug)->first();

        return response()->json([
            'message'   => 'Show product by slug',
            'product'   => $product
        ]);
    }

    public function update(UpdateProductRequest $request,$slug)
    {
        try{
            $validated = $request->validated();

            if (isset($validated['name'])) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            $product = product::where('slug',$slug)->first();
            $product->update($validated);

            return response()->json([
                'message'   => 'Update product successfully',
                'product'   => $product
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Update product failed',
                'product'   => $e
            ]);
        }
    }

    public function destroy($slug)
    {
        $product = product::where('slug',$slug)->first();
        $product->delete();

        return response()->json([
            'message'   => 'Product delete successfully',
        ]);
    }
}
