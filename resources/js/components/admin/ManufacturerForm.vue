<template>
  <div class="manufacturer-form">
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <Sidebar class="sidebar" />

      <div class="main-content">
        <h2>Thêm nhà sản xuất</h2>

        <form @submit.prevent="createManufacturer" class="form-container">

          <div class="form-group">
            <label>Tên nhà sản xuất:</label>
            <input v-model="form.name" type="text" required />
          </div>

          <div class="form-group">
            <label>Quốc gia:</label>
            <input v-model="form.country" type="text" placeholder="Ví dụ: Việt Nam" />
          </div>

          <div class="form-group">
            <label>Mô tả:</label>
            <textarea v-model="form.description" placeholder="Giới thiệu về NSX"></textarea>
          </div>

          <button type="submit" class="btn btn-primary">Lưu nhà sản xuất</button>

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
        country: "",
        description: "",
      },
    };
  },

  methods: {
    async createManufacturer() {
      try {
        await axios.post(`${this.baseURL}/api/storeM`, this.form);

        alert("Thêm nhà sản xuất thành công!");
        this.$router.push("/admin/manufacturer-manager");
      } catch (err) {
        console.error("Lỗi khi thêm NSX:", err);
        alert("Không thể thêm nhà sản xuất!");
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
  width: 100%;
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
.form-group textarea {
  width: 100%;
  padding: 8px 10px;
  border-radius: 6px;
  border: 1px solid #ddd;
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
