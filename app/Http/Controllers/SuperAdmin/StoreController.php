<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\{Store, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $stores = Store::where('super_admin_id', $user->id)
            ->withCount(['users', 'products', 'invoices'])
            ->paginate(10);

        return view('superadmin.stores.index', compact('stores'));
    }

    public function create()
    {
        return view('superadmin.stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:stores,email',
        ]);

        $user = auth()->user();

        Store::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'email' => $request->email,
            'super_admin_id' => $user->id,
            'status' => 'active',
        ]);

        return redirect()->route('superadmin.stores.index')
            ->with('success', 'تم إضافة المتجر بنجاح');
    }

    public function show(Store $store)
    {
        $this->authorize('view', $store);

        $store->load(['users', 'products', 'invoices' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('superadmin.stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        $this->authorize('update', $store);
        return view('superadmin.stores.edit', compact('store'));
    }

    public function update(Request $request, Store $store)
    {
        $this->authorize('update', $store);

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:stores,email,' . $store->id,
            'status' => 'required|in:active,inactive',
        ]);

        $store->update($request->all());

        return redirect()->route('superadmin.stores.index')
            ->with('success', 'تم تحديث المتجر بنجاح');
    }

    public function destroy(Store $store)
    {
        $this->authorize('delete', $store);

        $store->delete();

        return redirect()->route('superadmin.stores.index')
            ->with('success', 'تم حذف المتجر بنجاح');
    }

    public function users(Store $store)
    {
        $this->authorize('view', $store);

        $users = $store->users()->paginate(10);

        return view('superadmin.stores.users', compact('store', 'users'));
    }

    public function createUser(Store $store)
    {
        $this->authorize('view', $store);

        return view('superadmin.stores.create-user', compact('store'));
    }

    public function storeUser(Request $request, Store $store)
    {
        $this->authorize('view', $store);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required|in:admin,cashier',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'store_id' => $store->id,
        ]);

        return redirect()->route('superadmin.stores.users', $store)
            ->with('success', 'تم إضافة المستخدم بنجاح');
    }
}
