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

          <table class="table-product">
  <thead>
    <tr>
      <th>Hình ảnh</th>
      <th>Tên sản phẩm</th>
      <th>Nhà sản xuất</th>
      <th>Mùi hương</th>
      <th>Giá</th>
      <!-- <th>Số lượng</th> -->
      <th>Hành động</th>
    </tr>
  </thead>

  <tbody>
    <tr v-for="product in paginatedProducts" :key="product.id">
      <td class="img-cell">
        <router-link :to="'/product/' + product.id">
          <img v-if="product.imageUrl" :src="product.imageUrl" class="img-small" />
          <span v-else class="text-red-500">Không có hình</span>
        </router-link>
      </td>

      <td>
        <router-link :to="'/product/' + product.id" class="product-link">
          {{ product.name }}
        </router-link>
      </td>

      <td>{{ getManufacturerName(product.manufacturer_id) }}</td>

      <td>{{ getScentName(product.scent_id) }}</td>

      <td>{{ formatCurrency(product.price) }}</td>

      <!-- <td>{{ product.quantity }}</td> -->

      <td>
        <button @click="deleteProduct(product.id)" class="btn btn-outline-danger btn-sm">Xóa</button>

        <router-link
          :to="'/admin/products/' + product.id + '/edit'"
          class="btn btn-outline-primary btn-sm"
        >
          Sửa
        </router-link>
      </td>
    </tr>
  </tbody>
</table>


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
      <router-link to="/admin/product-form" class="btn btn-primary btn-lg"> Thêm sản phẩm</router-link>
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
      pageSize: 6,

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
 this.products = response.data.map((p) => {
  let imageUrl = '';
  if (p.image) {
    const clean = p.image.replace(/\r?\n|\r/g, '').trim(); // loại bỏ cả \r\n
    if (clean.startsWith('http')) {
      imageUrl = clean;
    } else {
      imageUrl = `${this.baseURL}/${clean.replace(/^\/+/, '')}`;
    }
  }
  console.log('✅ imageUrl:', imageUrl);
  return { ...p, imageUrl };
});


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

/* Layout */
.admin-body {
  display: flex;
  margin-top: 80px;
}

/* Sidebar */
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

/* Content */
.main-content {
  flex: 1;
  margin-left: 270px;
  padding: 20px 40px;
  background: #fafafa;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 18px;
}

/* TABLE */
.table-product {
  width: 100%;
  border-collapse: collapse;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
}

.table-product th,
.table-product td {
  padding: 10px 12px;
  text-align: center;
  border-bottom: 1px solid #ddd;
}

.table-product th {
  background: #000;
  color: white;
}

/* IMAGE */
.img-cell {
  width: 190px;
}

.img-small {
  width: 170px;
  height: 170px;
  object-fit: cover;
  border-radius: 6px;
  border: 1px solid #ddd;
}

/* LINKS */
.product-link {
  color: #000;
  text-decoration: none;
  font-weight: 600;
}
.product-link:hover {
  text-decoration: underline;
}

/* BUTTONS */
.btn-outline-primary,
.btn-outline-danger {
  padding: 5px 10px;
  font-size: 14px;
  cursor: pointer;
  border-radius: 6px;

}

.btn-outline-primary {
  border: 1px solid #007bff;
  color: #007bff;
}
.btn-outline-danger {
  border: 1px solid #dc3545;
  color: #dc3545;
}

/* ADD button */
.add-product-button {
  display: inline-block;
  background-color: #000; /* nền đen */
  color: #fff; /* chữ trắng */
  position: fixed;
  bottom: 20px;
  right: 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  padding: 12px 20px;
}




/* PAGINATION */
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

/* Loading */
.loading {
  text-align: center;
  font-size: 18px;
  color: #333;
}

</style>
