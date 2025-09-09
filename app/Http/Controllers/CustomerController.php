<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers (for web interface)
     */
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->withCount('orders')
            ->paginate(20);
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer',
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer (THIS WAS MISSING!)
     */
    public function show($customer)
    {
        $customer = User::where('role', 'customer')
            ->with(['orders' => function($query) {
                $query->latest()->take(10);
            }])
            ->findOrFail($customer);

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit($customer)
    {
        $customer = User::where('role', 'customer')->findOrFail($customer);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, $customer)
    {
        $customerModel = User::where('role', 'customer')->findOrFail($customer);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $customerModel->id,
        ]);

        $customerModel->update($request->only(['name', 'email']));

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer
     */
    public function destroy($customer)
    {
        $customerModel = User::where('role', 'customer')->findOrFail($customer);
        $customerModel->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    // Keep your existing API methods for any AJAX functionality
    
    /**
     * API: Get all customers as JSON
     */
    public function apiIndex()
    {
        return response()->json([
            'customers' => User::where('role', 'customer')->get()
        ]);
    }

    /**
     * API: Get specific customer as JSON
     */
    public function apiShow($customer)
    {
        $customer = User::where('role', 'customer')->findOrFail($customer);
        return response()->json($customer);
    }

    /**
     * API: Update customer via JSON
     */
    public function apiUpdate(Request $request, $customer)
    {
        $customerModel = User::where('role', 'customer')->findOrFail($customer);
        $customerModel->update($request->all());
        return response()->json($customerModel);
    }

    /**
     * API: Delete customer via JSON
     */
    public function apiDestroy($customer)
    {
        $customerModel = User::where('role', 'customer')->findOrFail($customer);
        $customerModel->delete();
        return response()->json(['success' => true]);
    }
}