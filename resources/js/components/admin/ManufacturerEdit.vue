<template>
  <div class="manufacturer-edit">
    <HeaderAdmin class="header" />
    <div class="admin-body">
      <Sidebar class="sidebar" />

      <div class="main-content">
        <div class="form-wrapper">
          <h2 class="title">Chỉnh sửa Nhà Sản Xuất</h2>

          <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

          <form v-else @submit.prevent="updateManufacturer" class="form">
            <!-- Tên NSX -->
            <div class="form-group">
              <label>Tên nhà sản xuất:</label>
              <input v-model="manufacturer.name" type="text" class="form-control" required />
            </div>

            <!-- Địa chỉ -->
            <div class="form-group">
              <label>Địa chỉ:</label>
              <input v-model="manufacturer.country" type="text" class="form-control" />
            </div>

            <div class="form-group">
              <label>Email:</label>
              <input v-model="manufacturer.email" type="text" class="form-control" />
            </div>

            <div class="form-group">
              <label>Số điện thoại:</label>
              <input v-model="manufacturer.phone" type="text" class="form-control" />
            </div>

            <!-- Mô tả -->
            <div class="form-group">
              <label>Mô tả:</label>
              <textarea v-model="manufacturer.description" class="form-control"></textarea>
            </div>

            <!-- Nút -->
            <div class="form-actions">
              <button type="submit" class="btn btn-primary"> Lưu thay đổi</button>
              <router-link to="/admin/manufacturer-manager" class="btn btn-secondary">⬅ Quay lại</router-link>
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
  name: "ManufacturerEdit",
  components: { HeaderAdmin, Sidebar },
  data() {
    return {
      baseURL: "http://localhost:8000",
      manufacturer: {
        name: "",
        country: "",
        email:"",
        phone:"",
        description: "",
      },
      loading: false,
    };
  },
  mounted() {
    this.fetchManufacturer();
  },
  methods: {
    async fetchManufacturer() {
      const id = this.$route.params.id;
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/showM/${id}`);
        this.manufacturer = res.data;
      } catch (err) {
        console.error("Lỗi tải nhà sản xuất:", err);
      } finally {
        this.loading = false;
      }
    },

    async updateManufacturer() {
      const id = this.$route.params.id;
      try {
        await axios.put(`${this.baseURL}/api/updateM/${id}`, this.manufacturer);
        alert("✅ Cập nhật nhà sản xuất thành công!");
        this.$router.push("/admin/manufacturer-manager");
      } catch (err) {
        console.error("Lỗi cập nhật NSX:", err);
        alert("❌ Lỗi khi cập nhật nhà sản xuất!");
      }
    },
  },
};
</script>

<style scoped>
.admin-body {
  display: flex;
}

.sidebar {
  width: 250px;
  background: #f5f5f5;
  height: 100vh;
  position: fixed;
}

.main-content {
  flex: 1;
  margin-left: 300px;
  padding: 40px;
  background: #fafafa;
  min-height: 100vh;
}

.form-wrapper {
  width: 100%;
  background: white;
  padding: 32px;
  border-radius: 12px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
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
