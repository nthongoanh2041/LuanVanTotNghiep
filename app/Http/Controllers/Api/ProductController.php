<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Scent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Prompts\Prompt;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexP()
    {
       $products = Product::all()->map(function ($product) {
        // Nếu image tồn tại thì tạo full URL
        if ($product->image) {
            $product->image = asset($product->image);
        }
        return $product;
    });

    return response()->json($products);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeP(Request $request)
{
    // Validate dữ liệu đầu vào
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100|unique:products,name',
        'category_id' => 'required|exists:categories,id',
        'scent_id' => 'required|exists:scents,id',
        'manufacturer_id' => 'required|exists:manufacturers,id',
        'price' => 'required|numeric|between:0,999999.99',
        'quantity'=>'required|numeric',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ], 422);
    }

    // Tạo sản phẩm mới
    $product = new Product();
    $product->name = $request->name;
    $product->category_id = $request->category_id;
    $product->scent_id = $request->scent_id;
    $product->manufacturer_id = $request->manufacturer_id;
    $product->price = $request->price;
    $product->quantity = $request->quantity;
    $product->description = $request->description ?? '';

    // Upload ảnh (nếu có)
    if ($request->hasFile('image')) {
    $image = $request->file('image');
    $imageName = time() . '-' . $image->getClientOriginalName();
    $image->move(public_path('image/'), $imageName);
    $product->image = 'image/' . $imageName;
} elseif ($request->filled('image')) {
    // Nếu chỉ gửi link ảnh có sẵn
    $product->image = $request->image;
}


    $product->save();

    return response()->json([
        'status' => true,
        'message' => 'Thêm sản phẩm thành công',
        'data' => $product,
    ], 201);
}

    /**
     * Display the specified resource.
     */
    public function showP($id)
    {
        return response()->json(Product::findOrFail($id));
    }


    public function updateP(Request $request, $id)
    {

    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroyP(Product $product)
    {
        //
    }

    public function getProductsByCategory($id)
    {
        // Tìm danh mục
        $category = Category::find($id);

        // Kiểm tra xem danh mục có tồn tại hay không
        if (!$category) {
            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }

        // Lấy danh sách sản phẩm thuộc danh mục sử dụng relationship
        $products = $category->products;

        // Trả về danh sách sản phẩm dưới dạng JSON
        return response()->json($products);
    }
    public function getProductsByScent($id)
    {
        // Tìm danh mục
        $scent = Scent::find($id);

        // Kiểm tra xem danh mục có tồn tại hay không
        if (!$scent) {
            return response()->json(['message' => 'Danh mục không tồn tại'], 404);
        }

        // Lấy danh sách sản phẩm thuộc danh mục sử dụng relationship
        $products = $scent->products;

        // Trả về danh sách sản phẩm dưới dạng JSON
        return response()->json($products);
    }
}
