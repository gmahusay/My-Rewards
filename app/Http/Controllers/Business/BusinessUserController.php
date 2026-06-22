<?php

namespace App\Http\Controllers\Business;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class BusinessUserController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    public function indexEmployees()
    {
        $employees = auth()->user()->employees()->paginate(10);
        return view('business.employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new employee.
     */
    public function createEmployee()
    {
        return view('business.employees.create');
    }

    /**
     * Store a newly created employee in storage.
     */
    public function storeEmployee(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        auth()->user()->addEmployee($data);

        return redirect()->route('business.employees.index')->with('status', 'Employee created successfully!');
    }

    /**
     * Display a listing of the customers.
     */
    public function indexCustomers()
    {
        $customers = auth()->user()->customers()->paginate(10);
        return view('business.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function createCustomer()
    {
        return view('business.customers.create');
    }

    /**
     * Store a newly created customer in storage.
     */
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'max:1024'], // 1MB Max
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ];

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo_path'] = $path;
        }

        auth()->user()->addCustomer($data);

        return redirect()->route('business.customers.index')->with('status', 'Customer created successfully!');
    }
}
