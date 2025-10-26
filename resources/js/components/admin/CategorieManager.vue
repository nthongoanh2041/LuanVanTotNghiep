<template>
  <div class="category-list">
    <!-- Header cố định -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- Sidebar -->
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <div v-else>
          <h2 class="section-title">Danh sách loại sản phẩm</h2>

          <ul class="categorie-list-items">
            <li
              v-for="category in paginatedCategories"
              :key="category.id"
              class="categorie-card"
            >
              <img
                v-if="category.image"
                :src="category.image"
                alt="Ảnh loại sản phẩm"
                class="category-img"
              />
              <span v-else>—</span>
              <h3>{{ category.name }}</h3>
              <p>{{ category.description || '—' }}</p>
              <div class="card-actions">
                <router-link
                  :to="'/admin/categorie/' + category.id + '/edit'"
                  class="btn btn-outline-primary btn-sm"
                >
                  Sửa
                </router-link>
                <button
                  @click="deleteCategory(category.id)"
                  class="btn btn-outline-danger btn-sm"
                >
                  Xóa
                </button>
                <!-- Nút chuyển sang danh sách sản phẩm thuộc loại này -->
                <button
                  @click="goToCategoryDetail(category.id)"
                  class="btn btn-outline-success btn-sm"
                >
                  Sản phẩm
                </button>
              </div>
            </li>
          </ul>

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

          <!-- Thêm loại sản phẩm -->
          <div class="add-category-button">
            <router-link to="/admin/categorie-form" class="btn btn-primary btn-lg">
              + Thêm loại sản phẩm
            </router-link>
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
  name: "CategoryList",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      categories: [],
      loading: false,
      currentPage: 1,
      pageSize: 4, // 4 item mỗi trang
    };
  },
  computed: {
    filteredCategories() {
      return this.categories;
    },
    paginatedCategories() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.filteredCategories.slice(start, start + this.pageSize);
    },
    totalPages() {
      return Math.ceil(this.filteredCategories.length / this.pageSize);
    },
    pageNumbers() {
      return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      this.loading = true;
      try {
        const response = await axios.get(`${this.baseURL}/api/index`);
        this.categories = response.data;
      } catch (error) {
        console.error("Lỗi khi tải loại sản phẩm:", error);
      } finally {
        this.loading = false;
      }
    },
    async deleteCategory(id) {
      if (!confirm("Bạn có chắc chắn muốn xóa loại sản phẩm này?")) return;
      try {
        await axios.delete(`${this.baseURL}/api/categories/${id}`);
        this.categories = this.categories.filter((c) => c.id !== id);
        alert("Đã xóa loại sản phẩm!");
      } catch (error) {
        console.error("Lỗi khi xóa loại sản phẩm:", error);
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
    // <-- Thêm method chuyển trang danh sách sản phẩm theo category
    goToCategoryDetail(id) {
      this.$router.push(`/admin/categorie/${id}/products`);
    },
  },
};
</script>

<style scoped>
/* giữ nguyên style cũ */
.header { position: fixed;
     top: 0; left: 0; right: 0; z-index: 100; background: #fff; border-bottom: 1px solid #eee; }

.sidebar { position: fixed; top: 80px; left: 0; width: 250px; height: calc(100vh - 80px); background-color: #f9f9f9; border-right: 1px solid #ddd; padding: 20px; overflow-y: auto; }
.admin-body { display: flex; margin-top: 80px; }
.main-content { flex: 1; margin-left: 300px; padding: 20px 40px; min-height: calc(100vh - 80px); background-color: #fafafa; }
.section-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; }
.categorie-list-items { display:flex; grid-template-columns: repeat(4, 1fr); gap: 20px; list-style: none; padding: 0; margin-right: 15px; }
.categorie-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); text-align: center; }
.category-img { width: 200px; height: 200px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 10px; }
.card-actions { margin-top: 10px; display: flex; justify-content: center; gap: 10px; }
.btn-outline-primary { color: #007bff; border: 1px solid #007bff; padding: 6px 12px; font-size: 14px; }
.btn-outline-primary:hover { background: #007bff; color: #fff; }
.btn-outline-danger { color: #dc3545; border: 1px solid #dc3545; padding: 6px 12px; font-size: 14px; }
.btn-outline-danger:hover { background: #dc3545; color: #fff; }
.btn-outline-success { color: #28a745; border: 1px solid #28a745; padding: 6px 12px; font-size: 14px; }
.btn-outline-success:hover { background: #28a745; color: #fff; }
.add-category-button { margin-top: 20px; text-align: right; }
.pagination { display: flex; justify-content: center; margin-top: 20px; list-style: none; }
.page-item { margin: 0 5px; }
.page-link { padding: 6px 12px; font-size: 14px; cursor: pointer; color: #000; border: 1px solid #000; border-radius: 4px; }
.page-link:hover { background-color: #007bff; color: #fff; border-color: #007bff; }
.loading { text-align: center; font-size: 18px; color: #333; }
</style>
