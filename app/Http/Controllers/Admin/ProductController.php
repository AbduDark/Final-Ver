<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Product, Category};

class ProductController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $products = Product::where('store_id', $user->store_id)
            ->with('category')
            ->paginate(20);
        
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $user = auth()->user();
        $categories = Category::where('store_id', $user->store_id)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();
        
        $category = Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'store_id' => $user->store_id,
        ]);

        return response()->json([
            'success' => true,
            'category' => $category
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code',
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
        ]);

        $user = auth()->user();
        
        Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'category_id' => $request->category_id,
            'purchase_price' => $request->purchase_price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'min_quantity' => $request->min_quantity,
            'store_id' => $user->store_id,
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $user = auth()->user();
        $categories = Category::where('store_id', $user->store_id)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:products,code,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'min_quantity' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('admin.products.index')
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function lowStock()
    {
        $user = auth()->user();
        $products = Product::where('store_id', $user->store_id)
            ->whereRaw('quantity <= min_quantity')
            ->with('category')
            ->paginate(20);
        
        return view('admin.products.low-stock', compact('products'));
    }
}
