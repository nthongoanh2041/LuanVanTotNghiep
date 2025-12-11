<template>
  <div class="stock-product-page-wrapper">
    <!-- Header cố định -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- Sidebar cố định -->
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <h2 class="page-title">Danh sách sản phẩm tồn kho</h2>

        <!-- Search -->
        <div class="search-container">
  <router-link
    to="/admin/stock-history"
    class="btn-history"
  >
    Xem lịch sử phiếu nhập
  </router-link>
</div>

        <!-- Loading -->
        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <!-- Table -->
        <table v-else class="table-stock">
          <thead>
            <tr>
              <th>Hình ảnh</th>
              <th>Tên sản phẩm</th>
              <th>Số lượng</th>
              <th>Trạng thái</th>
              <th>Hành động</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="product in paginatedProducts" :key="product.id">
              <td class="img-cell">
                <img v-if="product.image" :src="product.image" class="img-small" />
                <span v-else class="text-red-500">Không có hình</span>
              </td>
              <td>{{ product.name }}</td>
              <td>{{ product.quantity }}</td>
              <td>
                <span :class="product.status === 'Còn hàng' ? 'text-green-600' : 'text-red-600'">
                  {{ product.status }}
                </span>
              </td>
               <td>
  <!-- Nút Đặt hàng khi sắp hết -->
  <router-link
    v-if="product.status === 'Sắp hết hàng'"
    :to="'/admin/stock-request/' + product.id"
    class="btn btn-warning btn-sm"
  >
    Đặt hàng
  </router-link>

  <!-- Nút Nhập hàng -->
  <router-link
    v-if="product.status === 'Sắp hết hàng'"
    :to="'/admin/stock-import/' + product.id"
    class="btn btn-warning btn-sm"
  >
    Nhập hàng
  </router-link>
</td>
            </tr>
          </tbody>
        </table>


        <!-- Pagination -->
        <nav v-if="totalPages > 1" aria-label="Pagination">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button class="page-link" @click="previousPage" :disabled="currentPage === 1">
                Trang trước
              </button>
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
              <button class="page-link" @click="nextPage" :disabled="currentPage === totalPages">
                Trang sau
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "StockProduct",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      products: [],
      loading: false,
      baseURL: "http://localhost:8000",
      currentPage: 1,
      pageSize: 6,
      searchQuery: "",
    };
  },
  computed: {
    filteredProducts() {
      if (!this.searchQuery) return this.products;
      return this.products.filter((p) =>
        p.name.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    paginatedProducts() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredProducts.slice(start, start + this.pageSize);
    },
    totalPages() {
      return Math.ceil(this.filteredProducts.length / this.pageSize);
    },
    pageNumbers() {
      return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },
  },
  mounted() {
    this.fetchProducts();
  },

  methods: {
    async fetchProducts() {
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/stock`);
        this.products = res.data;
      } catch (err) {
        console.error("Lỗi khi tải sản phẩm tồn kho:", err);
      } finally {
        this.loading = false;
      }
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
  z-index: 100;
  background: #fff;
  border-bottom: 1px solid #eee;
}

.admin-body {
  display: flex;
  margin-top: 80px;
}

.sidebar {
  position: fixed;
  top: 80px;
  left: 0;
  width: 250px;
  background: #f9f9f9;
  border-right: 1px solid #ddd;
  padding: 20px;
  height: calc(100vh - 80px);
  overflow-y: auto;
}

.main-content {
  flex: 1;
  margin-left: 270px;
  padding: 20px 40px;
  background: #fafafa;
}

.page-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 18px;
}

.search-container {
  margin-bottom: 12px;
  text-align: right;
}

.search-input {
  padding: 6px 12px;
  border-radius: 6px;
  border: 1px solid #ccc;
  width: 250px;
}

.table-stock {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.table-stock th,
.table-stock td {
  padding: 12px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

.table-stock th {
  background: #000;
  color: white;
}

.img-cell {
  width: 120px;
}

.img-small {
  width: 170px;
  height: 170px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #ddd;
}

.table-stock tbody tr:hover {
  background: #f3f4f6;
}

.loading {
  text-align: center;
  font-size: 18px;
  color: #333;
}

.text-green-600 {
  color: #16a34a;
  font-weight: bold;
}

.text-red-600 {
  color: #dc2626;
  font-weight: bold;
}

.text-red-500 {
  color: #ef4444;
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
  border: 1px solid #000;
  padding: 5px 10px;
  background: white;
  cursor: pointer;
}

.page-item.active .page-link {
  background: #000;
  color: white;
}

.btn-warning {
  padding: 6px 12px;
  background: #000000;
  color: white;
  border-radius: 6px;
  text-decoration: none;
  font-weight: 600;
}
.btn-warning:hover {
  background: #845100;
}

</style>
