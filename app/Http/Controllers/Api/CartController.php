<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // 🟢 Lấy giỏ hàng của user
    public function indexC(Request $request)
    {
       try {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            $user_id = $request->user_id ?? null;

            if (!$user_id) {
                // Nếu không có user_id, lấy giỏ hàng từ session/cookie
                $cart = session()->get('cart');
                if (!$cart) {
                    return response()->json(['cartItems' => []], 200); // Trả về một giỏ hàng trống nếu không có sản phẩm trong session
                }

                // Chuyển đổi session giỏ hàng thành mảng để trả về
                $cartItems = array_values($cart);
                return response()->json(['cartItems' => $cartItems], 200);
            } else {
                // Nếu có user_id, lấy giỏ hàng từ cơ sở dữ liệu
                $cartItems = Cart::where('user_id', $user_id)->with('product')->get();

                return response()->json(['cartItems' => $cartItems], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to fetch cart items.'], 500);
        }
    }

    // 🟢 Thêm sản phẩm vào giỏ
   public function storeC(Request $request)
{
    // ✅ Validate request
    $request->validate([
        'product_id' => 'required|integer',
        'quantity' => 'required|integer|min:1',
        'total_amount' => 'required|numeric|min:0'
    ]);

    try {
        // ✅ Lấy user_id (nếu có)
        $user_id = $request->user_id ?? null;

        // ===========================
        // 🧩 Nếu chưa đăng nhập: lưu session
        // ===========================
        if (!$user_id) {
            $cart = session()->get('cart', []);

            // Nếu sản phẩm đã có trong giỏ → cập nhật số lượng
            if (isset($cart[$request->product_id])) {
                $cart[$request->product_id]['quantity'] += $request->quantity;
                $cart[$request->product_id]['total_amount'] += $request->total_amount;
            } else {
                // Nếu chưa có, thêm mới
                $cart[$request->product_id] = [
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'total_amount' => $request->total_amount
                ];
            }

            session()->put('cart', $cart);

            return response()->json(['message' => 'Đã thêm vào giỏ hàng (session).'], 200);
        }

        // ===========================
        // 🧩 Nếu đã đăng nhập: lưu database
        // ===========================
        $cartItem = \App\Models\Cart::where('user_id', $user_id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Nếu sản phẩm đã tồn tại → cập nhật
            $cartItem->quantity += $request->quantity;
            $cartItem->total_amount += $request->total_amount;
            $cartItem->save();
        } else {
            // Nếu chưa có → thêm mới
            $cartItem = \App\Models\Cart::create([
                'user_id' => $user_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_amount' => $request->total_amount
            ]);
        }

        return response()->json([
            'message' => '✅ Đã thêm sản phẩm vào giỏ hàng thành công!',
            'item' => $cartItem
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => '❌ Lỗi khi thêm vào giỏ hàng!',
            'error' => $e->getMessage()
        ], 500);
    }
}


    // 🟢 Cập nhật số lượng
    public function updateC(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cartItem = Cart::with('product')->findOrFail($id);
        $cartItem->update(['quantity' => $validated['quantity']]);
        $cartItem->total_amount = $cartItem->quantity * $cartItem->product->price;
        $cartItem->save();


        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công',
            'data' => $cartItem
        ]);
    }

    // 🟢 Xóa 1 sản phẩm khỏi giỏ
    public function destroyC($id)
    {
        $cartItem = Cart::findOrFail($id);
        if (!$cartItem) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng!'], 404);
        }

        $cartItem->delete();

        return response()->json(['message' => 'Đã xóa khỏi giỏ hàng']);
    }

    // 🟢 Xóa toàn bộ giỏ hàng
    public function clearC(Request $request)
    {
        $userId = $request->user()->id;

        Cart::where('user_id', $userId)->delete();

        return response()->json(['message' => 'Đã xóa toàn bộ giỏ hàng']);
    }
}
