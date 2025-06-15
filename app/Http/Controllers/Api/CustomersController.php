<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomersRequest;
use App\Http\Requests\UpdateCustomersRequest;

use App\Models\customers;

class CustomersController extends Controller
{
    public function index()
    {
        return response()->json([
            'message'   => 'view all customers',
            'customers' => customers::get()
        ]);
    }

    public function store(StoreCustomersRequest $request)
    {
        try{
            $validate = $request->validated();
            $validate['slug'] = Str::slug($validate['name']);
            $customers = customers::create($validate);
            return response()->json([
                'message'   =>'Customer created successfully',
                'customers' => $customers
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Customers Failed to created',
                'customers' => $e
            ]);
        }
    }

    public function show($slug)
    {
        $customers = customers::where('slug',$slug)->first();
        return response()->json([
            'message'   => 'Show customer by slug',
            'customers' => $customers
        ]);
    }

    public function update(UpdateCustomersRequest $request,$slug)
    {
        try{
            $validatedData = $request->validated();

            if (isset($validatedData['name'])) {
                $validatedData['slug'] = Str::slug($validatedData['name']);
            }

            $customers = customers::where('slug',$slug)->first();
            $customers->update($validatedData);

            return response()->json([
                'message'   => 'Customers update succesfully',
                'customers'=>$customers
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message'   => 'Customers update succesfully',
                'customers'=>$e
            ]);
        }
    }

    public function destroy($slug)
    {
        $customers = customers::where('slug',$slug)->first();
        $customers->delete();

        return response()->json([
            'message'   => 'Customer deleted',
        ]);
    }
}
