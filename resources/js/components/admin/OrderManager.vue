<template>
  <div class="order-manager">
    <!-- Header -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- Sidebar -->
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải đơn hàng...
        </div>

        <div v-else>
          <h2 class="section-title"> Danh sách đơn hàng</h2>

          <table class="order-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Địa chỉ</th>
                <th>Số điện thoại</th>
                <th>Tổng tiền</th>
                <th>Thanh toán</th>
                <th>Trạng thái</th>
                <th>Đã xử lý</th>
                <th>Ngày đặt</th>
                <th>Hành động</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="order in paginatedOrders" :key="order.id">
                <td>{{ order.id }}</td>
                <td>{{ order.customer_name }}</td>
                <td>{{ order.customer_address }}</td>
                <td>{{ order.customer_phone }}</td>
                <td>{{ formatCurrency(order.total_amount) }}</td>
                <td>
                  <span class="payment">
                    {{ order.payment_method === "cash" ? "Tiền mặt" : "Chuyển khoản" }}
                  </span>
                </td>
                <td>
                <select v-model="order.status" @change="updateOrder(order)">

                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đã xác nhận</option>
                    <option value="completed">Hoàn thành</option>
                    <option value="cancelled">Đã hủy</option>
                  </select>
                </td>
                    <td>
                  <input type="checkbox" v-model="order.is_processed" @change="updateOrder(order)" />


                    </td>



                <td>{{ formatDate(order.created_at) }}</td>
                <td>
                  <button class="btn btn-outline-info btn-sm" @click="viewOrder(order.id)">
                    Xem
                  </button>
                  <button
                    class="btn btn-outline-danger btn-sm"
                    @click="deleteOrder(order.id)"
                  >
                    Hủy
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Pagination -->
          <nav aria-label="Pagination">
            <ul class="pagination justify-content-center">
              <li class="page-item" :class="{ disabled: currentPage === 1 }">
                <button class="page-link" @click="previousPage" :disabled="currentPage === 1">Trang trước</button>
              </li>
              <li
                class="page-item"
                v-for="page in pageNumbers"
                :key="page"
                :class="{ active: currentPage === page }"
              >
                <button class="page-link" @click="goToPage(page)">{{ page }}</button>
              </li>
              <li class="page-item" :class="{ disabled: currentPage === totalPages }">
                <button class="page-link" @click="nextPage" :disabled="currentPage === totalPages">Trang sau</button>
              </li>
            </ul>
          </nav>
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
  name: "OrderManager",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      orders: [],
      loading: false,
      currentPage: 1,
      pageSize: 6,
    };
  },
  computed: {
    paginatedOrders() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.orders.slice(start, start + this.pageSize);
    },
    totalPages() {
      return Math.ceil(this.orders.length / this.pageSize);
    },
    pageNumbers() {
      return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },
  },
  mounted() {
    this.fetchOrders();

  },
  methods: {
   async fetchOrders() {
  this.loading = true;
  try {
    const res = await axios.get(`${this.baseURL}/api/indexO`);
    this.orders = res.data.map(order => ({
      ...order,
      // ép kiểu để hiển thị đúng
      is_processed: order.is_processed === 1,
    }));
  } catch (err) {
    console.error("❌ Lỗi khi tải đơn hàng:", err);
  } finally {
    this.loading = false;
  }
},

    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },

    formatDate(dateStr) {
      return new Date(dateStr).toLocaleString("vi-VN");
    },
async updateOrder(order) {
  try {
    await axios.put(`${this.baseURL}/api/updateO/${order.id}`, {
      is_processed: order.is_processed ? 1 : 0,
      status: order.is_processed ? 'processing' : 'pending',
    });
    alert(" Cập nhật trạng thái xử lý đơn hàng thành công!");
  } catch (err) {
    console.error("❌ Lỗi khi cập nhật xử lý:", err);
    alert("❌ Không thể cập nhật trạng thái xử lý!");
  }
},



    async deleteOrder(id) {
      if (!confirm("Bạn có chắc chắn muốn hủy đơn hàng này không?")) return;
      try {
        await axios.delete(`${this.baseURL}/api/orders/${id}`);
        this.orders = this.orders.filter((o) => o.id !== id);
        alert(" Đã hủy đơn hàng!");
      } catch (err) {
        console.error("❌ Lỗi khi xóa đơn hàng:", err);
      }
    },

    viewOrder(id) {
      this.$router.push(`/admin/orders/${id}`);
    },

    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    previousPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    goToPage(page) {
      this.currentPage = page;
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
  overflow-y: auto;
}

.admin-body {
  display: flex;
  margin-top: 80px;
}

.main-content {
  flex: 1;
  margin-left: 300px;
  padding: 20px 40px;
  min-height: calc(100vh - 80px);
  background-color: #fafafa;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.order-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.order-table th,
.order-table td {
  padding: 12px 15px;
  border-bottom: 1px solid #eee;
  text-align: center;
}

.order-table th {
  background: #f4f4f4;
  font-weight: bold;
}

.status-select {
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.btn {
  border: 1px solid;
  padding: 5px 10px;
  border-radius: 6px;
  font-size: 13px;
  cursor: pointer;
}

.btn-outline-info {
  border-color: #17a2b8;
  color: #17a2b8;
}

.btn-outline-info:hover {
  background: #17a2b8;
  color: #fff;
}

.btn-outline-danger {
  border-color: #dc3545;
  color: #dc3545;
}

.btn-outline-danger:hover {
  background: #dc3545;
  color: #fff;
}

.pagination {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  list-style: none;
}

.page-item {
  margin: 0 5px;
}

.page-link {
  padding: 6px 12px;
  border: 1px solid #000;
  border-radius: 4px;
  cursor: pointer;
}

.page-link:hover {
  background-color: #007bff;
  color: #fff;
  border-color: #007bff;
}
</style>
