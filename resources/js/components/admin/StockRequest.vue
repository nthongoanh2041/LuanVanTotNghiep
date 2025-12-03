<template>
  <div class="order-form-wrapper">
    <!-- HEADER CỐ ĐỊNH -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <!-- SIDEBAR -->
      <Sidebar class="sidebar" />

      <!-- NỘI DUNG CHÍNH -->
      <div class="main-content">
        <h2 class="page-title">Yêu cầu đặt hàng</h2>

        <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

        <form v-else class="order-form" @submit.prevent="submitRequest">

          <!-- Nhân viên yêu cầu -->
          <div class="form-group">
            <label>Nhân viên yêu cầu</label>
            <input type="text" :value="employeeName" readonly />
          </div>

          <!-- Ngày yêu cầu -->
          <div class="form-group">
            <label>Ngày yêu cầu</label>
            <input type="date" v-model="requestDate" required />
          </div>

          <!-- Tên sản phẩm -->
          <div class="form-group">
            <label>Tên sản phẩm</label>
            <input type="text" :value="product.name" readonly />
          </div>

          <!-- Nhà sản xuất -->
          <div class="form-group">
            <label>Nhà sản xuất</label>
            <input type="text" :value="manufacturer.name" readonly />
          </div>

          <div class="form-group">
            <label>Email nhà sản xuất</label>
            <input type="text" :value="manufacturer.email" readonly />
          </div>

          <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" :value="manufacturer.phone" readonly />
          </div>

          <!-- Số lượng -->
          <div class="form-group">
            <label>Số lượng cần đặt</label>
            <input type="number" v-model="quantity" min="10" required />
          </div>

          <div class="form-buttons">
            <button class="btn btn-primary" type="submit">Gửi yêu cầu</button>
            <router-link to="/admin/stock-manager" class="btn btn-secondary">
              Quay lại
            </router-link>
          </div>
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
  name: "OrderRequestForm",
  components: { HeaderAdmin, Sidebar },

  data() {
    return {
      baseURL: "http://localhost:8000",
      loading: false,
      product: {},
      manufacturer: {},
      // Lấy nhân viên từ localStorage, key "user"
    employeeName: sessionStorage.getItem("admin_info")
  ? JSON.parse(sessionStorage.getItem("admin_info")).name
  : "",

      requestDate: new Date().toISOString().substr(0, 10),
      quantity: 10,
    };
  },

  async mounted() {
    this.loading = true;
    try {
      // Load sản phẩm & nhà sản xuất
      await this.fetchProduct();
    } catch (err) {
      console.error("Lỗi khi tải dữ liệu:", err);
    } finally {
      this.loading = false;
    }
  },

  methods: {
    async fetchProduct() {
      const id = this.$route.params.id;
      try {
        const res = await axios.get(`${this.baseURL}/api/showP/${id}`);
        console.log("Product:", res.data);
        this.product = res.data;

        if (this.product.manufacturer_id) {
          const manu = await axios.get(
            `${this.baseURL}/api/showM/${this.product.manufacturer_id}`
          );
          console.log("Manufacturer:", manu.data);
          this.manufacturer = manu.data;
        }
      } catch (err) {
        console.error("Lỗi tải dữ liệu:", err);
      }
    },

   async submitRequest() {
  try {
    const payload = {
      product_id: this.product.id,
      manufacturer_id: this.manufacturer.id,
      quantity: this.quantity,
      contact_info: `${this.manufacturer.email} - ${this.manufacturer.phone}`,
      user_id: JSON.parse(sessionStorage.getItem("admin_info")).id,

      status: "Đã gửi", // mặc định khi gửi
    };

    await axios.post(`${this.baseURL}/api/manufacturer-request`, payload);

    alert("Yêu cầu đặt hàng đã được gửi thành công!");
    this.$router.push("/admin/stock-manager");
  } catch (err) {
    console.error("Lỗi gửi yêu cầu:", err);
    alert("Gửi yêu cầu thất bại, thử lại sau.");
  }
}
}

};
</script>

<style scoped>
.order-form {
  background: white;
  padding: 22px;
  border-radius: 8px;
  max-width: 650px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 16px;
}

.form-group label {
  font-weight: bold;
}

.form-group input {
  width: 100%;
  padding: 8px 12px;
  border: 1px solid #bbb;
  border-radius: 6px;
}

.form-buttons {
  display: flex;
  gap: 12px;
  margin-top: 20px;
}

.btn-primary {
  background: #000;
  color: white;
  padding: 10px 14px;
  border-radius: 6px;
  border: none;
}

.btn-secondary {
  background: #777;
  color: white;
  padding: 10px 14px;
  border-radius: 6px;
  text-decoration: none;
}

.admin-body {
  display: flex;
}

.sidebar {
  width: 280px;
  position: fixed;
  top: 0;
  bottom: 0;
  left: 0;
}

.main-content {
  margin-left: 380px;
  padding: 20px;
  width: calc(100% - 280px);
}
</style>
