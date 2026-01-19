<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Category, Product, Table};

class MasterController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();
        $products = Product::with('category')->orderBy('name')->get();
        $tables = Table::orderBy('code')->get();
        return view('admin.master', compact('categories', 'products', 'tables'));
    }

    public function saveCategory(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'name' => ['required', 'string', 'max:120'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable'],
        ]);

        Category::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'name' => $data['name'],
                'sort_order' => (int)($data['sort_order'] ?? 0),
                'is_active' => isset($data['is_active']) ? true : false,
            ]
        );

        return back();
    }

    public function saveProduct(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'stock_enabled' => ['nullable'],
            'stock_qty' => ['nullable', 'integer'],
            'is_active' => ['nullable'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        Product::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'category_id' => $data['category_id'],
                'name' => $data['name'],
                'price' => $data['price'],
                'cost' => $data['cost'] ?? 0,
                'stock_enabled' => isset($data['stock_enabled']) ? true : false,
                'stock_qty' => (int)($data['stock_qty'] ?? 0),
                'is_active' => isset($data['is_active']) ? true : false,
                'image_url' => $imagePath,
            ]
        );

        return back()->with('success', 'Produk ditambahkan.');
    }

    public function saveTable(Request $request)
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer'],
            'code' => ['required', 'string', 'max:10'],
            'name' => ['required', 'string', 'max:60'],
            'is_active' => ['nullable'],
        ]);

        Table::updateOrCreate(
            ['id' => $data['id'] ?? null],
            [
                'code' => $data['code'],
                'name' => $data['name'],
                'is_active' => isset($data['is_active']) ? true : false,
            ]
        );

        return back();
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'type' => ['required', 'in:category,product,table'],
            'id' => ['required', 'integer'],
        ]);

        if ($data['type'] === 'category') Category::where('id', $data['id'])->delete();
        if ($data['type'] === 'product') Product::where('id', $data['id'])->delete();
        if ($data['type'] === 'table') Table::where('id', $data['id'])->delete();

        return back();
    }

    public function updateCategory(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $data['name'],
            'sort_order' => $data['sort_order'] ?? 0,
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function updateProduct(Request $request, Product $product)
    {
        $data = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['nullable', 'string', 'max:60'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['nullable', 'numeric', 'min:0'],
            'stock_enabled' => ['nullable', 'boolean'],
            'stock_qty' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // sku unique kalau diisi
        if (!empty($data['sku'])) {
            $exists = Product::where('sku', $data['sku'])->where('id', '!=', $product->id)->exists();
            if ($exists) {
                return back()->with('error', 'SKU sudah dipakai produk lain.');
            }
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image_url = $imagePath;
        }

        $product->update([
            'category_id' => $data['category_id'],
            'name' => $data['name'],
            'sku' => $data['sku'] ?? null,
            'price' => $data['price'],
            'cost' => $data['cost'] ?? 0,
            'stock_enabled' => (bool)($data['stock_enabled'] ?? false),
            'stock_qty' => (int)($data['stock_qty'] ?? 0),
            'is_active' => (bool)($data['is_active'] ?? false),
            'image_url' => $product->image_url,
        ]);

        return back()->with('success', 'Produk diperbarui.');
    }

    public function updateTable(Request $request, \App\Models\Table $table)
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:60'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $exists = \App\Models\Table::where('code', $data['code'])->where('id', '!=', $table->id)->exists();
        if ($exists) {
            return back()->with('error', 'Kode meja sudah dipakai.');
        }

        $table->update([
            'code' => $data['code'],
            'name' => $data['name'],
            'is_active' => (bool)($data['is_active'] ?? false),
        ]);

        return back()->with('success', 'Meja diperbarui.');
    }
    public function toggleCategory(Request $request, Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();

        return back();
    }
    public function toggleProduct(Request $request, Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return back();
    }
    public function toggleTable(Request $request, Table $table)
    {
        $table->is_active = !$table->is_active;
        $table->save();

        return back();
    }
}