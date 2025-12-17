<h2>Xin chào {{ $order->customer_name }}!</h2>

<p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi.</p>

<p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
<p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount) }} VND</p>
<p><strong>Ngày đặt:</strong> {{ $order->order_date }}</p>

<p>Chúng tôi sẽ liên hệ để xác nhận và giao hàng sớm nhất.</p>

<p>Trân trọng,<br>Đội ngũ hỗ trợ</p>
