<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Yêu cầu đặt hàng</title>
</head>
<body style="font-family: Arial,
sans-serif;
background: #f4f4f4;
padding: 20px;">
<div style="max-width: 600px;
margin: 0 auto; background: #fff;
border-radius: 8px;
padding: 20px;">
    <h2 style="text-align: center; color: #333;"> Yêu cầu đặt hàng mới</h2>
    <p><strong>Tên SP:</strong> {{ $data['product_name'] }}</p>
    <p><strong>Số lượng:</strong> {{ $data['quantity'] }}</p>
    <p><strong>Nhà sản xuất:</strong> {{ $data['manufacturer'] }}</p>
    <p><strong>Nhân viên yêu cầu:</strong> {{ $data['staff'] }}</p>
    <hr style="margin: 20px 0;">
    <p style="font-size: 16px; color: #444;">
        Vui lòng phản hồi yêu cầu này bằng cách chọn một trong hai nút dưới đây:
    </p>
    <div style="text-align: center; margin-top: 20px;">
        <!-- Nút XÁC NHẬN -->
        <a href="{{ url('/request/confirm/' . $id) }}" class="btn-yes"
           style="
                background: #28a745;
                color: white;
                padding: 12px 20px;
                text-decoration: none;
                border-radius: 6px;
                font-size: 16px;
                margin-right: 10px;
            ">
             Xác nhận
        </a>
        <!-- Nút TỪ CHỐI -->
        <a href="{{ url('/request/reject/' . $id) }}" class="btn-no"
           style="
                background: #dc3545;
                color: white;
                padding: 12px 20px;
                text-decoration: none;
                border-radius: 6px;
                font-size: 16px;
                margin-left: 10px;
            ">
             Từ chối
        </a>
    </div>
</div>
</body>
</html>
