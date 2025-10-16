<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()//để lấy toàn bộ ds- phương thức get
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)// để thêm -phương thức post
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->validate(['description' => '|string|max:255']);
        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    public function show($id)// để xem chi tiết theo id - get
    {
        return response()->json(Category::findOrFail($id));
    }

    public function update(Request $request, $id)// để sửa loại sp theo id - put
{
    // Tìm category theo id
    $category = Category::find($id);

    // Nếu không tìm thấy thì trả lỗi 404
    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    // Validate dữ liệu (tuỳ cột trong bảng của bạn)
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    // Cập nhật thông tin
    $category->update($validatedData);

    return response()->json([
        'message' => 'Category updated successfully',
        'data' => $category
    ], 200);
}

public function destroy($id)/// xóa theo id - delete
{
    $category = Category::find($id);

    if (!$category) {
        return response()->json(['message' => 'Category not found'], 404);
    }

    $category->delete();

    return response()->json(['message' => 'Category deleted successfully'], 200);
}

}
