<template>
  <div class="product-list">
    <!-- Header cố định -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- Sidebar cố định -->
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <div v-else>
          <h2 class="section-title">Danh sách sản phẩm</h2>

          <ul class="product-list-items">
            <li
              v-for="product in paginatedProducts"
              :key="product.id"
              class="product-list-item"
            >
              <router-link :to="'/product/' + product.id" class="product-link">
                <div class="product-frame">
                  <img :src="product.imageUrl" :alt="product.name" class="product-img" />
                </div>
              </router-link>

              <div class="product-details">
                <router-link :to="'/product/' + product.id" class="product-link">
                  <h5 class="product-name">{{ product.name }}</h5>
                </router-link>

                <p class="product-manufacturer">
                  Nhà sản xuất:
                  <span v-if="product.manufacturer_id">{{ getManufacturerName(product.manufacturer_id) }}</span>
                  <span v-else class="text-muted">Chưa có</span>
                </p>

                <p class="product-type">
                  Mùi hương:
                  <span v-if="product.scent_id">{{ getScentName(product.scent_id) }}</span>
                  <span v-else class="text-muted">Chưa có</span>
                </p>

                <p class="product-price">{{ formatCurrency(product.price) }}</p>

                <div class="product-actions">
                  <button @click="deleteProduct(product.id)" class="btn btn-outline-danger btn-sm">Xóa</button>
                  <router-link :to="'/admin/product/' + product.id + '/edit'" class="btn btn-outline-primary btn-sm">
                    Sửa
                  </router-link>
                </div>
              </div>
            </li>
          </ul>

          <!-- Phân trang -->
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

    <!-- Nút thêm sản phẩm -->
    <div class="add-product-button">
      <router-link to="/admin/product-form" class="btn btn-primary btn-lg">+ Thêm sản phẩm</router-link>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "ProductList",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      products: [],
      manufacturers: {},
      scents: {},
      loading: false,
      currentPage: 1,
      pageSize: 5,
    };
  },
  computed: {
    filteredProducts() {
      return this.products;
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
        const response = await axios.get(`${this.baseURL}/api/indexP`);
        this.products = response.data.map((p) => ({
          ...p,
         imageUrl: p.image.startsWith('http') ? p.image : `${this.baseURL}/${p.image}`,

        }));

        const manufacturerIds = [...new Set(this.products.map(p => p.manufacturer_id).filter(Boolean))];
        const scentIds = [...new Set(this.products.map(p => p.scent_id).filter(Boolean))];

        await Promise.all([
          this.fetchManufacturers(manufacturerIds),
          this.fetchScents(scentIds),
        ]);
      } catch (err) {
        console.error("Lỗi khi tải sản phẩm:", err);
      } finally {
        this.loading = false;
      }
    },

    async fetchManufacturers(ids) {
      const requests = ids.map((id) => axios.get(`${this.baseURL}/api/showM/${id}`));
      const responses = await Promise.allSettled(requests);
      responses.forEach((res, i) => {
        if (res.status === "fulfilled") this.manufacturers[ids[i]] = res.value.data.name;
      });
    },

    async fetchScents(ids) {
      const requests = ids.map((id) => axios.get(`${this.baseURL}/api/showS/${id}`));
      const responses = await Promise.allSettled(requests);
      responses.forEach((res, i) => {
        if (res.status === "fulfilled") this.scents[ids[i]] = res.value.data.name;
      });
    },

    async deleteProduct(id) {
      if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) return;
      try {
        await axios.delete(`${this.baseURL}/api/products/${id}`);
        this.products = this.products.filter((p) => p.id !== id);
        alert("Đã xóa sản phẩm!");
      } catch (err) {
        console.error("Lỗi khi xóa sản phẩm:", err);
      }
    },

    getManufacturerName(id) {
      return this.manufacturers[id] || "Chưa có";
    },
    getScentName(id) {
      return this.scents[id] || "Chưa có";
    },
    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(value);
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

/* Sidebar cố định */
.sidebar {
  position: fixed;
  top: 80px; /* trừ chiều cao header */
  left: 0;
  width: 250px;
  height: calc(100vh - 80px);
  background-color: #f9f9f9;
  border-right: 1px solid #ddd;
  padding: 20px;
  overflow-y: auto;
  z-index: 90;
}

/* Body chính */
.admin-body {
  display: flex;
  margin-top: 80px;
}

/* Phần nội dung đẩy sang phải để không bị sidebar đè */
.main-content {
  flex: 1;
  margin-left: 300px; /* đẩy ra khỏi sidebar */
  padding: 20px 40px;
  min-height: calc(100vh - 80px);
  background-color: #fafafa;
}

/* Danh sách sản phẩm */
.section-title {
  font-size: 24px;
  font-weight: 700;
  margin-bottom: 20px;
  color: #333;
}

.product-list-items {
  list-style: none;
  padding: 0;
}

.product-list-item {
  display: flex;
  align-items: center;
  padding: 12px;
  margin-bottom: 12px;
  border: 1px solid #ddd;
  border-radius: 10px;
  background: #fff;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s;
}
.product-list-item:hover {
  transform: translateY(-4px);
}

.product-frame {
  width: 100px;
  height: 100px;
  margin-right: 15px;
  border-radius: 8px;
  overflow: hidden;
}
.product-img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* .product-details {
  flex: 1;
} */

.product-name {
  font-size: 16px;
  font-weight: bold;
  margin-bottom: 6px;
  color: #222;
}

.product-manufacturer,
.product-type {
  font-size: 14px;
  color: #666;
  margin-bottom: 4px;
}

.product-price {
  font-size: 15px;
  font-weight: bold;
  color: #007bff;
  margin-top: 4px;
}

.product-actions {
  margin-top: 10px;
  display: flex;
  justify-content: flex-end;
}

.btn {
  padding: 6px 12px;
  font-size: 14px;
  cursor: pointer;
}

.btn-outline-primary {
  color: #007bff;
  border: 1px solid #007bff;
  margin-left: 5px;
}
.btn-outline-primary:hover {
  background: #007bff;
  color: #fff;
}

.btn-outline-danger {
  color: #dc3545;
  border: 1px solid #dc3545;
}
.btn-outline-danger:hover {
  background: #dc3545;
  color: #fff;
}

.add-product-button {
  position: fixed;
  bottom: 20px;
  right: 20px;
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
  font-size: 14px;
  cursor: pointer;
  color: #007bff;
  border: 1px solid #007bff;
  border-radius: 4px;
}

.page-link:hover {
  background-color: #007bff;
  color: #fff;
  border-color: #007bff;
}

.loading {
  text-align: center;
  font-size: 18px;
  color: #333;
}
</style>
