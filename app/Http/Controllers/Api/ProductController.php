<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str; 

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

    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'sku'   => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id'=> 'required',
            'is_available'=>'required'
        ]);

        try{
            $product = [
                'name'  => $request->name,
                'sku'   => $request->sku,
                'description'=>$request->description,
                'price'=> $request->price,
                'category_id'=> $request->category_id,
                'is_available'=> $request->is_available,
                'slug'  => Str::slug($request->name)
            ];

            product::create($product);

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

    public function update(Request $request,$slug)
    {
        $product = product::where('slug',$slug)->first();
        $request->validate([
            'name'  => 'required',
            'sku'   => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id'=> 'required',
            'is_available'=>'required'
        ]);

        try{
            $product->update([
                'name'  => $request->name,
                'sku'   => $request->sku,
                'description'=>$request->description,
                'price'=> $request->price,
                'category_id'=> $request->category_id,
                'is_available'=> $request->is_available,
                'slug'  => Str::slug($request->name)
            ]);

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
