<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function indexM()
    {
        return response()->json(Manufacturer::all());
    }

    public function storeM(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $request->validate(['country' => 'required|string|max:255']);
        $request->validate(['email' => 'required|string|max:255']);
        $request->validate(['phone' => 'required|string|max:255']);
        $request->validate(['description' => '|string|max:255']);
        $manufacturer = Manufacturer::create($request->all());
        return response()->json($manufacturer, 201);
    }

    public function showM($id)
    {
        return response()->json(Manufacturer::findOrFail($id));
    }

    public function updateM(Request $request, $id)
    {
        $manufacturer = Manufacturer::find($id);

    // Nếu không tìm thấy thì trả lỗi 404
    if (!$manufacturer) {
        return response()->json(['message' => 'Manufacturer not found'], 404);
    }

    // Validate dữ liệu (tuỳ cột trong bảng của bạn)
    $validatedData = $request->validate([
        'country'=>'required|string|max:255',
        'name' => 'required|string|max:255',
        'email' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'description' => 'nullable|string',
    ]);

    // Cập nhật thông tin
    $manufacturer->update($validatedData);

    return response()->json([
        'message' => 'Manufaturerc updated successfully',
        'data' => $manufacturer
    ], 200);
    }

    public function destroyM( $id)
    {
       $manufacturer = Manufacturer::find($id);

    if (!$manufacturer) {
        return response()->json(['message' => 'Manufacturer not found'], 404);
    }

    $manufacturer->delete();

    return response()->json(['message' => 'Manufacturer deleted successfully'], 200);
    }
}
