<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Order_items;
use App\Models\Cart;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function storeO(Request $request)
    {
        // ✅ Lấy user_id từ request (Vue gửi lên)
        $user_id = $request->user_id ?? null;

        // ✅ Xác thực dữ liệu đầu vào
        $validatedData = $request->validate([
            'customer.name'            => 'required|string|max:255',
            'customer.address'         => 'required|string|max:255',
            'customer.phone'           => 'required|string|max:20',
            'customer.payment_method'  => 'required|string|in:cash,bank_transfer',
            'cart'                     => 'required|array|min:1',
            'total'                    => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // 🧾 Tạo đơn hàng
            $order = Order::create([
                'customer_name'    => $validatedData['customer']['name'],
                'customer_address' => $validatedData['customer']['address'],
                'customer_phone'   => $validatedData['customer']['phone'],
                'payment_method'   => $validatedData['customer']['payment_method'],
                'order_date'       => now(),
                'total_amount'     => $validatedData['total'],
                'status'           => 'pending',
                'user_id'          => $user_id, // 👈 chỉ cần biến này
            ]);

            // 💳 Lưu các sản phẩm trong giỏ hàng vào bảng order_items
            foreach ($validatedData['cart'] as $item) {
                Order_items::create([
                    'order_id'     => $order->id,
                    'product_id'   => $item['product_id'] ?? $item['product']['id'] ?? null, // ✅ linh hoạt key
                    'price'        => $item['product']['price'] ?? $item['price'] ?? 0,
                    'quantity'     => intval($item['quantity'] ?? 1),
                    'total_amount' => $item['total_amount'] ?? ($item['product']['price'] * $item['quantity']),
                ]);
            }

            // 🧹 Xóa giỏ hàng sau khi đặt hàng
            if ($user_id) {
                Cart::where('user_id', $user_id)->delete();
            }

            DB::commit();

            return response()->json([
                'message' => '🎉 Đặt hàng thành công!',
                'order'   => $order,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => '❌ Đặt hàng thất bại!',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
    public function indexO()
    {
        try {
        // Lấy tất cả đơn hàng kèm theo chi tiết sản phẩm (nếu có quan hệ trong model)
            $orders = Order::with('orderItems.product') // nếu có khai báo quan hệ trong model Order
                ->orderBy('id', 'desc')
                ->get();

                return response()->json($orders, 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => '❌ Không thể tải danh sách đơn hàng',
            'error' => $e->getMessage()
        ], 500);
    }
}

public function updateO(Request $request, $id)
{
    try {
        $order = Order::with('orderItems')->find($id);

        if (!$order) {
            return response()->json(['message' => '❌ Không tìm thấy đơn hàng!'], 404);
        }

        $validatedData = $request->validate([
            'status' => 'nullable|string|in:pending,processing,completed,cancelled',
            'is_processed' => 'nullable|boolean',
        ]);

        // --- Nếu cập nhật STATUS ---
        if ($request->has('status')) {

            // 🟡 Nếu status chuyển sang processing → trừ số lượng
            if ($request->status === 'processing' && $order->is_processed == 0) {

                foreach ($order->orderItems as $item) {
                    $product = $item->product;

                    if ($product->quantity < $item->quantity) {
                        return response()->json([
                            "message" => "❌ Sản phẩm ".$product->name." không đủ số lượng!",
                        ], 400);
                    }

                    // 🔻 Trừ số lượng trong kho
                    $product->quantity -= $item->quantity;
                    $product->save();
                }

                // Đánh dấu đã xử lý để không trừ lần nữa
                $order->is_processed = 1;
            }

            $order->status = $request->status;
        }

        if ($request->has('is_processed')) {
            $order->is_processed = $request->is_processed ? 1 : 0;
        }

        $order->save();

        return response()->json([
            'message' => '✅ Cập nhật đơn hàng thành công!',
            'order' => $order,
        ], 200);

    } catch (\Exception $e) {
        return response()->json([
            'message' => '❌ Cập nhật đơn hàng thất bại!',
            'error' => $e->getMessage(),
        ], 500);
    }
}



public function showO($id)
{
    $order = Order::with('order_items.product')->find($id);

    if (!$order) {
        return response()->json(['message' => 'Không tìm thấy đơn hàng'], 404);
    }

    return response()->json([
        'order' => $order,
        'items' => $order->order_items,
    ]);
}



}
