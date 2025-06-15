<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCustomersRequest;

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

    public function store(Request $request)
    {
        try{

        }
    }
}
