<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Response;
use Inertia\Inertia;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Http\Requests\Customer\CustomerStoreRequest;
use Illuminate\Support\Str;

use App\Models\customers;
class CustomerController extends Controller
{
    public function index()
    {
        return Inertia::render('customer/index', [
            'customers' => customers::latest()->get(),
        ]);
    }

    public function store(CustomerStoreRequest $request)
    {
        customers::create($request->validated());
        return redirect()->back()->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function update(CustomerUpdateRequest $request, Customer $customer)
    {
        $customer->update($request->validated());
        return redirect()->back()->with('success', 'Data pelanggan berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->back()->with('success', 'Pelanggan berhasil dihapus.');
    }
}
