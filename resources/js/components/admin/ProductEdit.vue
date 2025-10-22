<template>
  <div class="product-edit">
    <HeaderAdmin class="header" />
    <div class="admin-body">
      <Sidebar class="sidebar" />
      <div class="main-content">
        <div class="form-wrapper">
          <h2 class="title">Chỉnh sửa sản phẩm</h2>

          <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

          <form v-else @submit.prevent="updateProduct" class="form">
            <!-- Tên sản phẩm -->
            <div class="form-group">
              <label>Tên sản phẩm:</label>
              <input v-model="product.name" type="text" class="form-control" required />
            </div>

            <!-- Danh mục -->
            <div class="form-group">
              <label>Danh mục:</label>
              <select v-model="product.category_id" class="form-control" required>
                <option disabled value="">-- Chọn danh mục --</option>
                <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                  {{ cat.name }}
                </option>
              </select>
            </div>

            <!-- Mùi hương -->
            <div class="form-group">
              <label>Mùi hương:</label>
              <select v-model="product.scent_id" class="form-control" required>
                <option disabled value="">-- Chọn mùi hương --</option>
                <option v-for="s in scents" :key="s.id" :value="s.id">{{ s.name }}</option>
              </select>
            </div>

            <!-- Nhà sản xuất -->
            <div class="form-group">
              <label>Nhà sản xuất:</label>
              <select v-model="product.manufacturer_id" class="form-control" required>
                <option disabled value="">-- Chọn NSX --</option>
                <option v-for="m in manufacturers" :key="m.id" :value="m.id">
                  {{ m.name }}
                </option>
              </select>
            </div>

            <!-- Giá -->
            <div class="form-group">
              <label>Giá (VND):</label>
              <input v-model="product.price" type="number" class="form-control" required />
            </div>

            <!-- Số lượng -->
            <div class="form-group">
              <label>Số lượng:</label>
              <input v-model="product.quantity" type="number" class="form-control" required />
            </div>

            <!-- Mô tả -->
            <div class="form-group">
              <label>Mô tả:</label>
              <textarea v-model="product.description" class="form-control"></textarea>
            </div>

            <!-- Ảnh -->
            <div class="form-group">
              <label>Ảnh sản phẩm:</label>
              <div v-if="product.image" class="image-preview">
                <img :src="product.image" alt="preview" />
              </div>
              <input type="file" @change="handleFileChange" class="form-control" />
            </div>

            <!-- Nút -->
            <div class="form-actions">
              <button type="submit" class="btn btn-primary">💾 Lưu thay đổi</button>
              <router-link to="/admin/products" class="btn btn-secondary">⬅ Quay lại</router-link>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import HeaderAdmin from "./HeaderAdmin.vue";
import Sidebar from "@/components/SideBar.vue";

export default {
  name: "ProductEdit",
  components: { HeaderAdmin, Sidebar },
  data() {
    return {
      baseURL: "http://localhost:8000",
      product: {
        name: "",
        category_id: "",
        scent_id: "",
        manufacturer_id: "",
        price: "",
        quantity: "",
        description: "",
        image: "",
      },
      categories: [],
      scents: [],
      manufacturers: [],
      imageFile: null,
      loading: false,
    };
  },
  mounted() {
    this.fetchProduct();
    this.fetchCategories();
    this.fetchScents();
    this.fetchManufacturers();
  },
  methods: {
    async fetchProduct() {
      const id = this.$route.params.id;
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/showP/${id}`);
        this.product = res.data;
      } catch (err) {
        console.error("Lỗi tải sản phẩm:", err);
      } finally {
        this.loading = false;
      }
    },

    async fetchCategories() {
      const res = await axios.get(`${this.baseURL}/api/index`);
      this.categories = res.data;
    },
    async fetchScents() {
      const res = await axios.get(`${this.baseURL}/api/indexS`);
      this.scents = res.data;
    },
    async fetchManufacturers() {
      const res = await axios.get(`${this.baseURL}/api/indexM`);
      this.manufacturers = res.data;
    },

    handleFileChange(e) {
      this.imageFile = e.target.files[0];
    },

    async updateProduct() {
  const id = this.$route.params.id;
  const formData = new FormData();

  const fields = [
    "name", "category_id", "scent_id",
    "manufacturer_id", "price", "quantity",
    "description"
  ];

  fields.forEach(key => formData.append(key, this.product[key]));

  if (this.imageFile) {
    formData.append("image", this.imageFile);
  }

  try {
    await axios.post(`${this.baseURL}/api/products/${id}?_method=PUT`, formData, {
      headers: { "Content-Type": "multipart/form-data" },
    });
    alert("✅ Cập nhật sản phẩm thành công!");
    this.$router.push("/admin/product-manager");
  } catch (err) {
    console.error("Lỗi cập nhật:", err);
    alert("❌ Lỗi khi cập nhật sản phẩm!");
  }
}
  },
};
</script>

<style scoped>
.admin-body {
  display: flex;
}

/* Giữ sidebar bên trái */
.sidebar {
  width: 250px;
  background: #f5f5f5;
  height: 100vh;
  position: fixed;
}

/* Vùng nội dung chính */
.main-content {
 flex: 1;
  margin-left: 300px;
  padding: 40px;
  background: #fafafa;
  min-height: 100vh;
}

/* Form chiếm full vùng nội dung */
.form-wrapper {
  width: 100%;
  background: white;
  padding: 32px;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
  box-sizing: border-box;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  font-weight: 600;
  display: block;
  margin-bottom: 6px;
}

.form-control {
  width: 100%;
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
}

.image-preview img {
  margin-top: 8px;
  width: 150px;
  border-radius: 8px;
  border: 1px solid #eee;
}

.form-actions {
  margin-top: 20px;
  display: flex;
  gap: 10px;
}

.btn-primary {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-secondary {
  background: #ccc;
  color: #333;
  padding: 10px 16px;
  border-radius: 6px;
  text-decoration: none;
}
</style>

