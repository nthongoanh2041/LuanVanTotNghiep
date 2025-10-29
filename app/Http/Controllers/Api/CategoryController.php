<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 🟢 Lấy danh sách tất cả category
    public function index()
    {
       $categories = Category::all()->map(function ($category) {
        if ($category->image) {
            $category->image = asset($category->image);
        }
        return $category;
    });

    return response()->json($categories, 200);
}

    // 🟢 Thêm mới category
   public function store(Request $request)
{
    // Validate dữ liệu đầu vào
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    // Xử lý upload ảnh
    if ($request->hasFile('image')) {
        // Lưu ảnh vào storage/app/public/categories
        $path = $request->file('image')->store('categories', 'public');

        // Lưu đường dẫn public để truy cập qua trình duyệt
       $validatedData['image'] = asset('storage/' . $path);

    } elseif ($request->filled('image')) {
        // Nếu chỉ gửi link ảnh có sẵn
        $validatedData['image'] = $request->image;
    }

    // Tạo category
    $category = Category::create($validatedData);

    return response()->json([
        'message' => 'Category created successfully',
        'data' => $category,
    ], 201);
}
    // 🟢 Hiển thị chi tiết 1 category theo ID
    public function show($id)
    {
        $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Nếu có ảnh, thêm full URL để Vue hiển thị đúng
    if ($category->image) {
        $category->image = asset($category->image);
    }

    return response()->json($category, 200);
    }

    // 🟢 Cập nhật category theo ID
  public function update(Request $request, $id)
{
    $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'old_image' => 'nullable|string',
    ]);

    // 🟢 Nếu có upload ảnh mới
    if ($request->hasFile('image')) {
        if ($category->image) {
            $oldImagePath = str_replace(asset('storage/'), '', $category->image);
            if (file_exists(storage_path('app/public/' . $oldImagePath))) {
                unlink(storage_path('app/public/' . $oldImagePath));
            }
        }
        $path = $request->file('image')->store('categories', 'public');
        $validatedData['image'] = asset('storage/' . $path);
    }
    // 🟢 Nếu không có ảnh mới thì giữ lại ảnh cũ
    elseif ($request->filled('old_image')) {
        $validatedData['image'] = $request->old_image;
    }

    $category->update($validatedData);

    return response()->json([
        'message' => 'Category updated successfully',
        'data' => $category
    ], 200);
}


    // 🟢 Xóa category theo ID
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        // Xóa ảnh nếu có
        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully'], 200);
    }
    // 🟢 Lấy tất cả sản phẩm theo loại
public function products($id)
{
    $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'Không tìm thấy loại sản phẩm'], 404);
    }

    // Lấy danh sách sản phẩm thuộc loại này (kèm tên loại)
    $products = $category->products()->get();

    // Trả về JSON có category_name để Vue hiển thị
    return response()->json([
        'category_name' => $category->name,
        'products' => $products
    ]);
}
}
