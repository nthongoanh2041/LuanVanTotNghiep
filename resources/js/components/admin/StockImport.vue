<template>
  <div class="admin-layout">

    <!-- HEADER CỐ ĐỊNH -->
    <HeaderAdmin class="header" />

    <!-- SIDEBAR CỐ ĐỊNH -->
    <Sidebar class="sidebar" />

    <!-- NỘI DUNG TRANG -->
    <main class="content">
      <h1 class="page-title">Phiếu nhập hàng</h1>

      <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

      <div v-else class="form-box">

        <!-- Thông tin sản phẩm -->
        <section class="product-info">
          <h3>Thông tin sản phẩm</h3>
          <p><strong>Tên:</strong> {{ product.name }}</p>
          <p><strong>Số lượng hiện tại:</strong> {{ product.quantity }}</p>
        </section>

        <!-- Form nhập hàng -->
        <form @submit.prevent="submitImport">
            <div class="form-group">
            <label>Nhân viên yêu cầu</label>
            <input type="text" :value="employeeName" readonly />
          </div>

          <label>Số lượng nhập</label>
          <input type="number" v-model="quantity" required />

          <div class="form-group">
            <label>Ngày nhập hàng</label>
            <input type="date" v-model="importDate" required readonly />
          </div>

          <label>Ghi chú</label>
          <textarea v-model="note"></textarea>

          <button type="submit" class="btn-submit">Nhập hàng</button>
          <router-link to="/admin/stock-manager" class="btn btn-secondary">
               Hủy
            </router-link>


        </form>
      </div>
    </main>

  </div>
</template>

<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  components: { Sidebar, HeaderAdmin },

  data() {
    return {
      product: {},
      quantity: "",
      note: "",
        // Lấy nhân viên từ localStorage, key "user"
    employeeName: sessionStorage.getItem("admin_info")
  ? JSON.parse(sessionStorage.getItem("admin_info")).name
  : "",
      importDate: new Date().toISOString().slice(0, 10),
      loading: false,
      baseURL: "http://localhost:8000",
    };
  },

  mounted() {
    this.loadProduct();
  },

  methods: {
    async loadProduct() {
      this.loading = true;
      const id = this.$route.params.id;

      try {
        const res = await axios.get(`${this.baseURL}/api/showP/${id}`);
        this.product = res.data;
      } catch (err) {
        console.error("Lỗi tải sản phẩm:", err);
      } finally {
        this.loading = false;
      }
    },

    async submitImport() {
     const id = this.$route.params.id; // LẤY ID SẢN PHẨM

         const data = {
         product_id: this.product.id,
         quantity: this.quantity,
         note: this.note,
         import_date: this.importDate,
         user_id: JSON.parse(sessionStorage.getItem("admin_info")).id,
     };

     try {
        await axios.post(`${this.baseURL}/api/import/${id}`, data);
         alert("Tạo phiếu nhập thành công!");
         this.$router.push("/admin/stock-manager");
     } catch (err) {
        console.error("Lỗi khi tạo phiếu nhập:", err);
    }
},
  },
};
</script>

<style scoped>

/* ===== LAYOUT CHUẨN ADMIN ===== */
.admin-layout {
  background: #f5f6fa;
  min-height: 100vh;
}

/* Sidebar cố định bên trái */
/* Sidebar cố định bên trái */
.sidebar {
  width: 260px;
  height: calc(100vh - 65px); /* chiều cao trừ header */
  position: fixed;
  left: 0;
  top: 65px; /* BẮT ĐẦU TỪ DƯỚI HEADER */
  background: #111827;
  color: white;
  z-index: 200;
  overflow-y: auto; /* scroll nếu sidebar dài */
}

/* Header cố định trên cùng */
.header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%; /* header full width */
  height: 65px;
  background: white;
  z-index: 300;
  box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

/* Nội dung chính */
.content {
  margin-left: 260px;
  padding: 100px 35px 40px;  /* chừa header */
}
/* Tiêu đề trang */
.page-title {
  font-size: 32px;
  font-weight: 700;
  margin-bottom: 25px;
  margin-left: 30px;
}

/* ===== BOX CHÍNH ===== */
.form-box {
  background: white;
  padding: 35px;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.08);
  width: 700px;
}

/* Thông tin SP */
.product-info {
  background: #f1f5f9;
  padding: 15px 20px;
  border-radius: 10px;
  margin-bottom: 20px;
  border-left: 5px solid #2563eb;
}

.product-info h3 {
  margin-bottom: 10px;
  font-size: 20px;
  font-weight: 600;
  color: #1e3a8a;
}

/* Input form */
label {
  display: block;
  font-weight: 600;
  margin: 12px 0 6px;
}

input,
textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 15px;
}

textarea {
  height: 110px;
  resize: none;
}

/* Button */
.btn-submit {
  background: #111827;
  color: white;
  padding: 10px 18px;
  border-radius: 8px;
  margin-top: 15px;
  border: none;
  cursor: pointer;
  font-weight: 600;
}

.btn-submit:hover {
  background: #1f2937;
}

/* Loading */
.loading {
  font-size: 18px;
  font-weight: 500;
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
