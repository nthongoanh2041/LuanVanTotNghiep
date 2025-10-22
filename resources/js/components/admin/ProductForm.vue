<template>
  <div class="product-form">
    <HeaderAdmin class="header" />
    <div class="admin-body">
      <Sidebar class="sidebar" />

      <div class="main-content">
        <h2>Thêm sản phẩm mới</h2>

        <form @submit.prevent="createProduct" class="form-container">
          <div class="form-group">
            <label>Tên sản phẩm:</label>
            <input v-model="form.name" type="text" required />
          </div>

          <div class="form-group">
            <label>Danh mục:</label>
            <select v-model="form.category_id" required>
              <option value="">-- Chọn danh mục --</option>
              <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label>Mùi hương:</label>
            <select v-model="form.scent_id">
              <option value="">-- Chọn mùi hương --</option>
              <option v-for="s in scents" :key="s.id" :value="s.id">{{ s.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label>Nhà sản xuất:</label>
            <select v-model="form.manufacturer_id">
              <option value="">-- Chọn NSX --</option>
              <option v-for="m in manufacturers" :key="m.id" :value="m.id">{{ m.name }}</option>
            </select>
          </div>

          <div class="form-group">
            <label>Giá (VND):</label>
            <input v-model="form.price" type="number" min="0" required />
          </div>

          <div class="form-group">
            <label>Số lượng:</label>
            <input v-model="form.quantity" type="number" min="0" />
          </div>

          <div class="form-group">
            <label>Mô tả:</label>
            <textarea v-model="form.description"></textarea>
          </div>

          <div class="form-group">
            <label>Hình ảnh:</label>
            <input type="file" @change="handleImageUpload" accept="image/*" />
            <div v-if="previewImage" class="preview">
              <img :src="previewImage" alt="Xem trước" />
            </div>
          </div>

          <button type="submit" class="btn btn-primary">Lưu sản phẩm</button>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import HeaderAdmin from "./HeaderAdmin.vue";
import Sidebar from "@/components/SideBar.vue";

export default {
  components: { HeaderAdmin, Sidebar },
  data() {
    return {
      baseURL: "http://localhost:8000",
      form: {
        name: "",
        category_id: "",
        scent_id: "",
        manufacturer_id: "",
        price: "",
        quantity: "",
        description: "",
        image: null,
      },
      categories: [],
      scents: [],
      manufacturers: [],
      previewImage: null,
    };
  },
  mounted() {
    this.fetchSelectData();
  },
  methods: {
    async fetchSelectData() {
      try {
        const [catRes, scentRes, manuRes] = await Promise.all([
          axios.get(`${this.baseURL}/api/index`),
          axios.get(`${this.baseURL}/api/indexS`),
          axios.get(`${this.baseURL}/api/indexM`),
        ]);
        this.categories = catRes.data;
        this.scents = scentRes.data;
        this.manufacturers = manuRes.data;
      } catch (error) {
        console.error("Lỗi khi tải dữ liệu:", error);
      }
    },

    handleImageUpload(e) {
      const file = e.target.files[0];
      if (file) {
        this.form.image = file;
        this.previewImage = URL.createObjectURL(file);
      }
    },

    async createProduct() {
      try {
        const formData = new FormData();
        for (let key in this.form) {
          formData.append(key, this.form[key]);
        }

        await axios.post(`${this.baseURL}/api/storeP`, formData, {
          headers: { "Content-Type": "multipart/form-data" },
        });

        alert("Thêm sản phẩm thành công!");
        this.$router.push("/admin/product-manager");
      } catch (err) {
        console.error("Lỗi khi thêm sản phẩm:", err);
        alert("Không thể thêm sản phẩm!");
      }
    },
  },
};
</script>

<style scoped>
.main-content {
  flex: 1;
  margin-left: 300px;
  padding: 40px;
  background: #fafafa;
  min-height: 100vh;

}

.form-container {
  background: #fff;
  padding: 32px;
  border-radius: 10px;
  width:100%;
  max-width: 100%;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  box-sizing: border-box;
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  display: block;
  font-weight: 600;
  margin-bottom: 6px;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
}

.preview img {
  margin-top: 10px;
  width: 150px;
  border-radius: 8px;
  border: 1px solid #eee;
}

.btn-primary {
  background: #007bff;
  color: white;
  border: none;
  padding: 10px 16px;
  border-radius: 6px;
  cursor: pointer;
}
</style>
