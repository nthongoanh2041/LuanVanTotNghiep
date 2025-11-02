<template>
  <div class="order-items-page">
    <!-- Header cố định -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- Sidebar -->
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <h2 class="section-title">Chi tiết đơn hàng #{{ orderId }}</h2>

        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <div v-else>
          <!-- Thông tin đơn hàng -->
          <div class="order-info" v-if="order">
            <p><strong>Tên khách hàng:</strong> {{ order.customer_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ order.customer_phone }}</p>
            <p><strong>Địa chỉ:</strong> {{ order.customer_address }}</p>
            <p><strong>Phương thức thanh toán:</strong> {{ order.payment_method }}</p>
            <p><strong>Trạng thái:</strong>
              <span :class="['status-badge', order.status]">{{ order.status }}</span>
            </p>
            <p><strong>Tổng tiền:</strong> {{ formatPrice(order.total_amount) }} ₫</p>
          </div>

          <!-- Danh sách sản phẩm -->
          <table class="order-table" v-if="orderItems.length">
            <thead>
              <tr>
                <th>#</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Tổng</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item, index) in orderItems" :key="item.id">
                <td>{{ index + 1 }}</td>
                <td>
                  <img
                    :src="item.product?.image || defaultImage"
                    alt="Ảnh sản phẩm"
                    class="product-img"
                  />
                </td>
                <td>{{ item.product?.name || 'Không rõ' }}</td>
                <td>{{ formatPrice(item.price) }} ₫</td>
                <td>{{ item.quantity }}</td>
                <td>{{ formatPrice(item.total_amount) }} ₫</td>
              </tr>
            </tbody>
          </table>

          <p v-else class="no-data">Không có sản phẩm nào trong đơn hàng này.</p>

          <!-- Nút quay lại -->
          <div class="actions">
            <router-link to="/admin/order-list" class="btn-back">⬅ Quay lại danh sách đơn hàng</router-link>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "OrderItemsManager",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      orderId: this.$route.params.id,
      order: null,
      orderItems: [],
      loading: false,
      defaultImage: "/images/no-image.png", // ảnh mặc định khi không có ảnh
    };
  },
  mounted() {
    this.fetchOrderDetails();
  },
  methods: {
    // 🧹 Hàm làm sạch ảnh
    formatImage(path) {
      if (!path) return this.defaultImage;
      const clean = path.replace(/[\r\n\s]+/g, "").trim();
      if (clean.startsWith("http")) return clean;
      return `${this.baseURL}/${clean.replace(/^\/+/, "")}`;
    },

    // 🔍 Lấy chi tiết đơn hàng + xử lý ảnh
    async fetchOrderDetails() {
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/showO/${this.orderId}`);
        this.order = res.data.order;

        this.orderItems = res.data.items.map((item) => ({
          ...item,
          product: {
            ...item.product,
            image: this.formatImage(item.product?.image),
          },
        }));
      } catch (error) {
        console.error("❌ Lỗi khi tải chi tiết đơn hàng:", error);
      } finally {
        this.loading = false;
      }
    },

    formatPrice(value) {
      return new Intl.NumberFormat("vi-VN").format(value);
    },
  },
};
</script>

<style scoped>
.header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background: #fff;
  border-bottom: 1px solid #eee;
  z-index: 100;
}

.sidebar {
  position: fixed;
  top: 80px;
  left: 0;
  width: 250px;
  height: calc(100vh - 80px);
  background-color: #f9f9f9;
  border-right: 1px solid #ddd;
  padding: 20px;
}

.admin-body {
  display: flex;
  margin-top: 80px;
}

.main-content {
  flex: 1;
  margin-left: 300px;
  padding: 30px;
  background-color: #fafafa;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.order-info {
  background: #fff;
  border-radius: 10px;
  padding: 20px;
  margin-bottom: 20px;
  line-height: 1.8;
}

.status-badge {
  padding: 4px 8px;
  border-radius: 6px;
  font-weight: 600;
  text-transform: capitalize;
}
.status-badge.pending {
  background: #fff3cd;
  color: #856404;
}
.status-badge.completed {
  background: #d4edda;
  color: #155724;
}
.status-badge.cancelled {
  background: #f8d7da;
  color: #721c24;
}

.order-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
}

.order-table th,
.order-table td {
  padding: 12px 16px;
  border-bottom: 1px solid #eee;
  text-align: center;
}

.order-table th {
  background: #f1f1f1;
  font-weight: 600;
}

.product-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 8px;
}

.actions {
  margin-top: 20px;
  text-align: right;
}

.btn-back {
  background: #000;
  color: #fff;
  padding: 10px 16px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s ease;
}
.btn-back:hover {
  background: #333;
}

.no-data {
  text-align: center;
  color: #777;
  font-style: italic;
}
</style>
