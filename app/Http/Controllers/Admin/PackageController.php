<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $packages = Package::where('store_id', $user->store_id)
            ->paginate(20);

        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $user = auth()->user();

        Package::create([
            'name' => $request->name,
            'network' => $request->network,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'store_id' => $user->store_id,
        ]);

        return redirect()->route('admin.packages.index')
            ->with('success', 'تم إضافة الباقة بنجاح');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'network' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $package->update($request->all());

        return redirect()->route('admin.packages.index')
            ->with('success', 'تم تحديث الباقة بنجاح');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')
            ->with('success', 'تم حذف الباقة بنجاح');
    }
}
