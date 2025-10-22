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
          <h2 class="section-title">Quản lý loại sản phẩm</h2>

          <table class="table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Tên loại</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="category in categories" :key="category.id">
                <td>{{ category.id }}</td>
                <td>{{ category.name }}</td>
                <td>{{ category.description || '—' }}</td>
                <td>
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
                </td>
              </tr>
            </tbody>
          </table>

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
    };
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

.table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border-radius: 10px;
  overflow: hidden;
}

.table th,
.table td {
  border: 1px solid #ddd;
  padding: 12px;
  text-align: left;
}

.table th {
  background-color: #f2f2f2;
  font-weight: bold;
}

.btn {
  padding: 6px 12px;
  font-size: 14px;
  cursor: pointer;
}

.btn-outline-primary {
  color: #007bff;
  border: 1px solid #007bff;
  margin-right: 5px;
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

.add-category-button {
  margin-top: 20px;
  text-align: right;
}
</style>
