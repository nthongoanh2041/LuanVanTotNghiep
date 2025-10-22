<template>
  <div class="category-form">
    <!-- Header & Sidebar -->
    <HeaderAdmin class="header" />
    <div class="admin-body">
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <h2 class="section-title">Thêm loại sản phẩm mới</h2>

        <form @submit.prevent="addCategory" class="form-container">
          <div class="form-group">
            <label for="name">Tên loại sản phẩm</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="form-control"
              placeholder="Nhập tên loại (VD: Nến thơm, Tinh dầu...)"
              required
            />
          </div>

          <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea
              v-model="form.description"
              id="description"
              class="form-control"
              placeholder="Mô tả ngắn về loại sản phẩm"
              rows="4"
            ></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">Lưu</button>
            <router-link to="/admin/categories" class="btn btn-secondary">
              Hủy
            </router-link>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "CategoryForm",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      form: {
        name: "",
        description: "",
      },
    };
  },
  methods: {
    async addCategory() {
      try {
        const response = await axios.post(`${this.baseURL}/api/store`, this.form);
        alert("Thêm loại sản phẩm thành công!");
        this.$router.push("/admin/categorie-list");
      } catch (error) {
        console.error("Lỗi khi thêm loại sản phẩm:", error);
        alert("Không thể thêm loại sản phẩm. Vui lòng kiểm tra lại!");
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
  padding: 40px;
  min-height: calc(100vh - 80px);
  background-color: #fafafa;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

.form-container {
  background: #fff;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  max-width: 600px;
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  font-weight: 600;
  margin-bottom: 8px;
}

.form-control {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 6px;
  font-size: 14px;
}

.form-control:focus {
  border-color: #007bff;
  outline: none;
}

.form-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.btn {
  padding: 8px 18px;
  font-size: 14px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-primary {
  background: #007bff;
  color: #fff;
  border: none;
}

.btn-primary:hover {
  background: #0056b3;
}

.btn-secondary {
  background: #ccc;
  color: #000;
  text-decoration: none;
  padding: 8px 18px;
  border-radius: 6px;
}

.btn-secondary:hover {
  background: #aaa;
}
</style>
