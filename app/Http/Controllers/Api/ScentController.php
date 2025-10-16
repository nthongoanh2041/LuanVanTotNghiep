<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scent;
use Illuminate\Http\Request;

class ScentController extends Controller
{
    public function indexS()
    {
        return response()->json(Scent::all());
    }

    public function storeS(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->validate(['description' => '|string|max:255']);
        $scent = Scent::create($request->all());
        return response()->json($scent, 201);
    }

    public function showS( $id)
    {
         return response()->json(Scent::findOrFail($id));
    }

    public function updateS(Request $request, $id)// để sửa mùi theo id - put
{
    // Tìm scent theo id
    $scent = Scent::find($id);

    // Nếu không tìm thấy thì trả lỗi 404
    if (!$scent) {
        return response()->json(['message' => 'Scent not found'], 404);
    }

    // Validate dữ liệu (tuỳ cột trong bảng của bạn)
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    // Cập nhật thông tin
    $scent->update($validatedData);

    return response()->json([
        'message' => 'Scent updated successfully',
        'data' => $scent
    ], 200);
}

public function destroy($id)/// xóa theo id - delete
{
    $scent = Scent::find($id);

    if (!$scent) {
        return response()->json(['message' => 'Scent not found'], 404);
    }

    $scent->delete();

    return response()->json(['message' => 'Scent deleted successfully'], 200);
}

public function getProductsByScent($id)
{
    // Tìm mùi hương theo id
    $scent = Scent::with('products')->find($id);

    if (!$scent) {
        return response()->json(['message' => 'Scent not found'], 404);
    }

    // Trả về JSON gồm tên mùi hương và danh sách sản phẩm
    return response()->json([
        'scent_name' => $scent->name,
        'products' => $scent->products
    ]);
}

}
