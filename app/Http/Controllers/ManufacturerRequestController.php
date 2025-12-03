<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ManufacturerRequest;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestOrderMail;
use App\Models\Product;
use App\Models\User;
use App\Models\Manufacturer;
use App\Notifications\ManufacturerConfirmed;

class ManufacturerRequestController extends Controller
{
    // Lưu yêu cầu mới
  public function storeM(Request $request)
{
    // Validate
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'manufacturer_id' => 'required|exists:manufacturers,id',
        'quantity' => 'required|integer|min:1',
        'contact_info' => 'required|string|max:255',
        'status' => 'required|string',
        'user_id' => 'required|exists:users,id',
    ]);

    // Lưu DB
    $manufacturerRequest = ManufacturerRequest::create($request->all());

    // Lấy dữ liệu để gửi mail
    $product = Product::find($request->product_id);
    $manufacturer = Manufacturer::find($request->manufacturer_id);
    $user = User::find($request->user_id);

    // Chuẩn bị data cho mail view
    $mailData = [
        'product_name' => $product->name,
        'quantity' => $request->quantity,
        'manufacturer' => $manufacturer->name,
        'contact' => $request->contact_info,
        'staff' => $user->name,
         'request_id' => $manufacturerRequest->id,
    ];

    // Gửi mail bằng view
 Mail::send('emails.request_order', [
    'data' => $mailData,
    'id' => $manufacturerRequest->id
], function ($message) use ($manufacturer) {
    $message->to($manufacturer->email)
            ->subject('Thông báo yêu cầu đặt hàng mới');
});


    return response()->json([
        'status' => true,
        'message' => 'Yêu cầu đặt hàng đã được gửi và email đã được thông báo!',
        'data' => $manufacturerRequest
    ], 201);
}

public function confirm($id)
{
    // Lấy request theo ID
    $req = ManufacturerRequest::find($id);
    if (!$req) return "Không tìm thấy yêu cầu!";

    // Cập nhật trạng thái
    $req->status = "Đã xác nhận";
    $req->save();

    // Tìm user đã gửi yêu cầu (thường là nhân viên)
    $admin = User::find($req->user_id);

    if ($admin) {
        // Gửi thông báo cho người đã tạo yêu cầu
       $admin->notify(new ManufacturerConfirmed($req, "Đơn hàng đã được xác nhận"));

    }

    return "Nhà sản xuất đã xác nhận đơn hàng!";
}


public function reject($id)
{
    $req = ManufacturerRequest::find($id);
    if (!$req) return "Không tìm thấy yêu cầu!";

    $req->status = "Từ chối";
    $req->save();

    // Tìm user đã gửi yêu cầu (thường là nhân viên)
    $admin = User::find($req->user_id);


   if ($admin) {
        // Gửi thông báo cho người đã tạo yêu cầu
       $admin->notify(new ManufacturerConfirmed($req, "Đơn hàng đã bị từ chối"));

    }

    return "Nhà sản xuất từ chối đơn hàng!";
}


}
