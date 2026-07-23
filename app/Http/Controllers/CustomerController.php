<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('name')->get();
        return view('manager.customers', compact('customers'));
    }

    public function store(Request $request)
    {
        if ($request->filled('phone')) {
            $rawDigits = preg_replace('/[^0-9]/', '', $request->phone);
            // If user typed 91 at the beginning of 12 digits, strip 91
            if (strlen($rawDigits) === 12 && str_starts_with($rawDigits, '91')) {
                $rawDigits = substr($rawDigits, 2);
            }
            $request->merge(['phone' => $rawDigits]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ], [
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = '+91 ' . $validated['phone'];
        }

        Customer::create($validated);
        return back()->with('success', 'Customer added successfully.');
    }

    public function update(Request $request, Customer $customer)
    {
        if ($request->filled('phone')) {
            $rawDigits = preg_replace('/[^0-9]/', '', $request->phone);
            if (strlen($rawDigits) === 12 && str_starts_with($rawDigits, '91')) {
                $rawDigits = substr($rawDigits, 2);
            }
            $request->merge(['phone' => $rawDigits]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'digits:10', 'regex:/^[6-9]\d{9}$/'],
            'billing_address' => 'nullable|string',
            'shipping_address' => 'nullable|string',
        ], [
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = '+91 ' . $validated['phone'];
        }

        $customer->update($validated);
        return back()->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return back()->with('success', 'Customer deleted successfully.');
    }
}
