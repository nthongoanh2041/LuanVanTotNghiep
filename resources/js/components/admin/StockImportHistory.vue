<template>
  <div class="wrapper">

    <!-- HEADER CỐ ĐỊNH -->
    <HeaderAdmin class="header" />

    <div class="layout">

      <!-- SIDEBAR -->
      <Sidebar class="sidebar" />

      <!-- CONTENT -->
      <div class="content">
        <h2 class="page-title">Lịch sử phiếu nhập hàng</h2>

        <div v-if="loading" class="loading">
          <i class="fa fa-spinner fa-spin"></i> Đang tải dữ liệu...
        </div>

        <table v-else class="table-stock">
          <thead>
            <tr>
              <th>Mã phiếu</th>
              <th>Tên sản phẩm</th>
              <th>Hành động</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="item in paginatedHistory"
              :key="item.id"
              class="clickable-row"
            >
              <td>{{ item.id }}</td>
              <td>{{ item.product?.name }}</td>
              <td> <button class="btn-view" @click.stop="goToDetail(item.id)">
                Xem
                    </button>
              </td>
            </tr>
          </tbody>
        </table>
        <button class="back-btn" @click="goBack">Quay lại</button>

        <!-- PAGINATION -->
        <nav v-if="totalPages > 1" aria-label="Pagination">
          <ul class="pagination justify-content-center">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button class="page-link" @click="previousPage">Trang trước</button>
            </li>

            <li
              class="page-item"
              v-for="page in pageNumbers"
              :key="page"
              :class="{ active: currentPage === page }"
            >
              <button class="page-link" @click="goToPage(page)">{{ page }}</button>
            </li>

            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <button class="page-link" @click="nextPage">Trang sau</button>
            </li>
          </ul>
        </nav>

      </div>
    </div>


  </div>
</template>
<script>
import axios from "axios";
import Sidebar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "StockImportHistory",
  components: { Sidebar, HeaderAdmin },

  data() {
    return {
      history: [],
      loading: false,
      baseURL: "http://localhost:8000",
      currentPage: 1,
      pageSize: 7,

      showPopup: false,
      selected: null
    };
  },

  computed: {
    paginatedHistory() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.history.slice(start, start + this.pageSize);
    },
    totalPages() {
      return Math.ceil(this.history.length / this.pageSize);
    },
    pageNumbers() {
      return Array.from({ length: this.totalPages }, (_, i) => i + 1);
    },
  },

  mounted() {
    this.fetchHistory();
  },

  methods: {
    async fetchHistory() {
      this.loading = true;
      try {
        const res = await axios.get(`${this.baseURL}/api/import/history`);
        this.history = res.data;
      } catch (err) {
        console.error("Lỗi khi tải lịch sử nhập hàng:", err);
      } finally {
        this.loading = false;
      }
    },

     goToDetail(id) {
    this.$router.push(`/admin/import/${id}`);
    },
    goBack() {
      this.$router.back();
    },


    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    previousPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    goToPage(page) {
      this.currentPage = page;
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
  min-height: 100vh;
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

.table-stock {
  width: 100%;
  border-collapse: collapse;
  background: white;
}

.table-stock th,
.table-stock td {
  padding: 10px;
  border: 1px solid #ddd;
}

.clickable-row {
  cursor: pointer;
}
.clickable-row:hover {
  background-color: #f0f8ff;
}
 .btn-view{
  margin-top: 15px;
  padding: 8px 15px;
  background: #016334;
  border: none;
  color: white;
  border-radius: 5px;
  cursor: pointer;
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
</style>
