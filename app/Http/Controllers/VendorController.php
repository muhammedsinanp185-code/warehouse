<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $vendors = Vendor::orderBy('name')->get();
        return view('manager.vendors', compact('vendors'));
    }

    public function store(Request $request)
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
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ], [
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = '+91 ' . $validated['phone'];
        }

        Vendor::create($validated);
        return back()->with('success', 'Vendor added successfully.');
    }

    public function update(Request $request, Vendor $vendor)
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
            'address' => 'nullable|string',
            'tax_id' => 'nullable|string|max:50',
        ], [
            'phone.digits' => 'The phone number must be exactly 10 digits.',
            'phone.regex' => 'Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9.',
        ]);

        if (!empty($validated['phone'])) {
            $validated['phone'] = '+91 ' . $validated['phone'];
        }

        $vendor->update($validated);
        return back()->with('success', 'Vendor updated successfully.');
    }

    public function destroy(Vendor $vendor)
    {
        $vendor->delete();
        return back()->with('success', 'Vendor deleted successfully.');
    }
}
