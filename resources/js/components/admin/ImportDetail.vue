<template>
  <div class="wrapper">

    <!-- HEADER -->
    <HeaderAdmin class="header" />

    <div class="layout">

      <!-- SIDEBAR -->
      <Sidebar class="sidebar" />

      <!-- CONTENT -->
      <div class="content">

        <h2 class="page-title">Chi tiết phiếu nhập {{ id }}</h2>

        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <div v-else-if="!detail">
          <p class="error">Không tìm thấy phiếu nhập!</p>
        </div>

        <div v-else class="detail-box">
          <p><strong>Mã phiếu:</strong> {{ detail.id }}</p>
          <p><strong>Sản phẩm:</strong> {{ detail.product?.name }}</p>
          <p><strong>Số lượng:</strong> {{ detail.quantity }}</p>
          <p><strong>Nhân viên:</strong> {{ detail.user?.name }}</p>
          <p><strong>Ngày nhập:</strong> {{ detail.import_date }}</p>
          <p><strong>Ghi chú:</strong> {{ detail.note }}</p>

          <button class="back-btn" @click="goBack">Quay lại</button>
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
  name: "ImportDetail",

  components: { Sidebar, HeaderAdmin },

  data() {
    return {
      id: this.$route.params.id,
      detail: null,
      loading: false,
      baseURL: "http://localhost:8000",
    };
  },

  mounted() {
    this.fetchDetail();
  },

  methods: {
    async fetchDetail() {
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/import/history/${this.id}`);
        this.detail = res.data;
      } catch (err) {
        console.error("Lỗi khi tải chi tiết phiếu nhập:", err);
      } finally {
        this.loading = false;
      }
    },

    goBack() {
      this.$router.back();
    },
  },
};
</script>

<style scoped>
.header {
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 50;
}

.layout {
  display: flex;
  margin-top: 70px;
}

.sidebar {
  width: 240px;
  position: fixed;
  top: 70px;
  left: 0;
  background: #fff;
  border-right: 1px solid #ddd;
}

.content {
  margin-left: 330px;
  padding: 20px;
  width: calc(100% - 240px);
}

.detail-box {
  background: white;
  padding: 20px;
  border-radius: 6px;
  border: 1px solid #ddd;
  max-width: 500px;
}

.back-btn {
  margin-top: 20px;
  padding: 8px 16px;
  background: #007bff;
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

.back-btn:hover {
  opacity: 0.9;
}

.error {
  color: red;
  font-weight: bold;
}
</style>
