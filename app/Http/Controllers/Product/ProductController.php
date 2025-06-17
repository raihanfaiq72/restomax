<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use Inertia\Response;
use Inertia\Inertia;
use Illuminate\Support\Str;

use App\Models\product;
use App\Models\category;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('product/index', [
            'products' => product::with('category')->latest()->get(),
            'categories' => category::select('id', 'name')->get(),
        ]);
    }

    public function store(ProductStoreRequest $request)
    {
        product::create($request->validated());

        return redirect()->back()->with('success', 'Product berhasil ditambahkan.');
    }

    public function update(ProductUpdateRequest $request,product $product)
    {
        $product->update($request->validated());

        return redirect()->back()->with('success','Data produk berhasil ditambahkan');
    }

    public function destroy(product $product)
    {
        $product->delete();

        return redirect()->back()->with('success','Produk berhasil dihapus');
    }
}
