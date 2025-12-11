<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Category;
use App\Models\Scent;
use App\Models\StockImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Prompts\Prompt;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexP()
    {$products = Product::all()->map(function ($product) {
        if ($product->image) {
            // Xóa khoảng trắng hoặc ký tự xuống dòng dư
            $cleanImage = trim($product->image);

            // Nếu image chưa có domain thì thêm asset()
            if (!str_starts_with($cleanImage, 'http')) {
                $cleanImage = asset($cleanImage);
            }

            $product->image = $cleanImage;
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
        'price' => 'required|numeric|min:0',
        // 'quantity'=>'required|numeric',
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
    // $product->quantity = $request->quantity;
    $product->description = $request->description ?? '';

    // Upload ảnh (nếu có)
if ($request->hasFile('image')) {
    $path = $request->file('image')->store('images', 'public');
    $product->image = 'storage/' . $path;
}
elseif ($request->filled('image')) {
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
    // Lấy sản phẩm kèm category và scent
    $product = Product::with(['category', 'scent'])->find($id);

    if (!$product) {
        return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
    }

    // Xử lý ảnh sản phẩm
    if ($product->image) {
        $cleanImage = trim($product->image);

        if (str_starts_with($cleanImage, 'http')) {
            // Nếu đã là URL tuyệt đối → giữ nguyên
            $product->image = $cleanImage;
        } else {
            // Nếu là tên file hoặc đường dẫn tương đối → trỏ vào storage/images
            $product->image = url('storage/images/' . basename($cleanImage));
        }
    } else {
        $product->image = null;
    }

    // Xử lý ảnh category (nếu muốn)
    if ($product->category && $product->category->image) {
        $catImage = trim($product->category->image);
        if (!str_starts_with($catImage, 'http')) {
            $product->category->image = url('storage/categories/' . basename($catImage));
        }
    }

    return response()->json($product);
}
    public function updateP(Request $request, $id)


{
    $product = Product::findOrFail($id);

    // Validate các field cơ bản
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'category_id' => 'required|exists:categories,id',
        'scent_id' => 'required|exists:scents,id',
        'manufacturer_id' => 'required|exists:manufacturers,id',
        'price' => 'required|numeric|min:0',
        // 'quantity' => 'required|integer|min:0',
        'description' => 'nullable|string',
        // ❌ KHÔNG ép validate image nếu không upload file
        'image' => 'nullable',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $data = $validator->validated();

    // 🖼 Nếu có file ảnh mới upload thì xử lý
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '-' . $image->getClientOriginalName();
        $image->move(public_path('image/'), $imageName);
        $data['image'] = 'image/' . $imageName;
    }

    // 🧹 Nếu không upload ảnh mới, thì giữ ảnh cũ
    else {
        unset($data['image']);
    }

    $product->update($data);

    return response()->json([
        'status' => true,
        'message' => 'Cập nhật sản phẩm thành công',
        'data' => $product
    ]);
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
     public function stock()
    {
        $products = Product::select('id', 'name', 'quantity', 'status', 'image')->get();

        $data = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->quantity,
                'status' => $product->status ? 'Còn hàng' : 'Sắp hết hàng',
                'image' => $product->image ? url($product->image) : null,
            ];
        });

        return response()->json($data);
    }

  public function importStock(Request $request, $id)
{
    // Validate dữ liệu
    $request->validate([
        'quantity' => 'required|integer|min:1',
        'note' => 'nullable|string',
        'user_id' => 'required|exists:users,id', // kiểm tra user tồn tại
    ]);

    // Lấy sản phẩm
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'message' => 'Không tìm thấy sản phẩm'
        ], 404);
    }

    // Tạo phiếu nhập
    $import = StockImport::create([
        'user_id'     => $request->user_id,
        'product_id'  => $product->id,
        'quantity'    => $request->quantity,
        'note'        => $request->note,
        'import_date' => now(), // ngày tạo phiếu nhập
    ]);

    // Cập nhật số lượng tồn trong bảng product
    $product->quantity += $request->quantity;

    // Cập nhật trạng thái
    if ($product->quantity > 20) {
        $product->status = "Còn hàng";
    }

    $product->save();

    return response()->json([
        'message' => 'Nhập hàng thành công!',
        'product' => $product,
        'import'  => $import
    ], 201);
}


}
