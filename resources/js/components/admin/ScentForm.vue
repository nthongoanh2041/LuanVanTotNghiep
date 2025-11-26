<template>
  <div class="scent-form">
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <Sidebar class="sidebar" />

      <div class="main-content">
        <h2>{{ isEdit ? "Cập nhật mùi hương" : "Thêm mùi hương" }}</h2>

        <form @submit.prevent="saveScent" class="form-container">

          <div class="form-group">
            <label>Tên mùi hương:</label>
            <input v-model="form.name" type="text" required />
          </div>

          <div class="form-group">
            <label>Mô tả:</label>
            <textarea v-model="form.description" placeholder="Mô tả mùi hương"></textarea>
          </div>
            <button type="submit" class="btn btn-primary">Lưu mùi hương</button>
            <router-link to="/admin/scent-manager" class="btn btn-secondary">
               Hủy
            </router-link>
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
      isEdit: false,
      id: null,
      form: {
        name: "",
        description: "",
      },
    };
  },

  async mounted() {
    this.id = this.$route.params.id;

    // Nếu có id → chế độ edit
    if (this.id) {
      this.isEdit = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/showS/${this.id}`);
        this.form = res.data;
      } catch (err) {
        console.error("Lỗi load mùi hương:", err);
      }
    }
  },

  methods: {
    async saveScent() {
      try {
        if (this.isEdit) {
          await axios.put(`${this.baseURL}/api/updateS/${this.id}`, this.form);
          alert("Cập nhật mùi hương thành công!");
        } else {
          await axios.post(`${this.baseURL}/api/storeS`, this.form);
          alert("Thêm mùi hương thành công!");
        }

        this.$router.push("/admin/scent-manager");
      } catch (err) {
        console.error("Lỗi lưu:", err);
        alert("Không thể lưu mùi hương!");
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
