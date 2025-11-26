<template>
  <div class="category-edit">
    <!-- Header & Sidebar -->
    <HeaderAdmin class="header" />
    <div class="admin-body">
      <Sidebar class="sidebar" />

      <!-- Nội dung chính -->
      <div class="main-content">
        <h2 class="section-title">Chỉnh sửa loại sản phẩm</h2>

        <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

        <form v-else @submit.prevent="updateCategory" class="form-container">
             <!-- Ảnh -->
          <div class="form-group">
            <label for="image">Ảnh loại sản phẩm</label>
            <input
              type="file"
              id="image"
              class="form-control"
              accept="image/*"
              @change="handleImageUpload"
            />

            <!-- Ảnh hiện tại -->
            <div v-if="form.image" class="preview">
              <p>Ảnh hiện tại:</p>
              <img :src="previewImage || `${baseURL}/${form.image}`" alt="Preview" class="preview-img" />
            </div>
          </div>

          <div class="form-group">
            <label for="name">Tên loại sản phẩm</label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="form-control"
              placeholder="Nhập tên loại sản phẩm"
              required
            />
          </div>

          <div class="form-group">
            <label for="description">Mô tả</label>
            <textarea
              v-model="form.description"
              id="description"
              class="form-control"
              rows="4"
              placeholder="Mô tả ngắn về loại sản phẩm"
            ></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
            <router-link to="/admin/categorie-manager" class="btn btn-secondary">
              ⬅ Quay lại
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
  name: "CategoryEdit",
  components: { Sidebar, HeaderAdmin },
  data() {
    return {
      baseURL: "http://localhost:8000",
      form: {
        name: "",
        description: "",
        image: "",
      },
      previewImage: null,
      loading: false,
    };
  },
  mounted() {
    this.fetchCategory();
  },
  methods: {
    async fetchCategory() {
      const id = this.$route.params.id;
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/show/${id}`);
        this.form = res.data;
      } catch (err) {
        console.error("Lỗi khi tải loại sản phẩm:", err);
        alert("Không thể tải loại sản phẩm!");
      } finally {
        this.loading = false;
      }
    },
    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        this.form.image = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },
    async updateCategory() {
  const id = this.$route.params.id;
  const formData = new FormData();

  formData.append("name", this.form.name);
  formData.append("description", this.form.description || "");

  // ✅ Nếu người dùng upload ảnh mới
  if (this.form.image instanceof File) {
    formData.append("image", this.form.image);
  }
  // ✅ Nếu KHÔNG upload ảnh mới, gửi lại link ảnh cũ để backend giữ nguyên
  else if (typeof this.form.image === "string" && this.form.image !== "") {
    formData.append("old_image", this.form.image);
  }

  try {
    await axios.post(`${this.baseURL}/api/update/${id}?_method=PUT`, formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });

    alert("✅ Cập nhật loại sản phẩm thành công!");
    this.$router.push("/admin/categorie-list");
  } catch (err) {
    console.error("Lỗi khi cập nhật:", err.response?.data || err);
    alert("❌ Lỗi khi cập nhật loại sản phẩm!");
  }
    },
  },
};
</script>


<style scoped>
.preview {
  margin-top: 10px;
}

.preview-img {
  width: 180px;
  height: auto;
  border-radius: 8px;
  border: 1px solid #ddd;
  margin-top: 5px;
}
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
