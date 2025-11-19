<template>
  <div class="scent-page">

    <HeaderAdmin class="header" />

    <div class="admin-body">

      <Sidebar class="sidebar" />

      <div class="main-content">
        <h2 class="section-title">Quản lý Mùi Hương</h2>

        <!-- Bảng -->
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>STT</th>
                <th>Tên mùi</th>
                <th>Mô tả</th>
                <th>Hành động</th>
              </tr>
            </thead>

            <tbody>
              <tr v-for="s in paginated" :key="s.id">
                <td>{{ s.id }}</td>
                <td>{{ s.name }}</td>
                <td>{{ s.description }}</td>
                <td>
                  <button @click="openEditForm(s)" class="btn btn-outline-primary btn-sm">Sửa</button>
                  <button @click="deleteScent(s.id)" class="btn btn-outline-danger btn-sm">Xoá</button>
                </td>
              </tr>

              <tr v-if="scents.length === 0">
                <td colspan="4" class="text-center">Chưa có mùi hương nào</td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <nav class="pagination-wrap" v-if="totalPages > 1">
          <ul class="pagination">
            <li class="page-item" :class="{ disabled: currentPage === 1 }">
              <button class="page-link" @click="prevPage">«</button>
            </li>

            <li
              v-for="page in totalPages"
              :key="page"
              class="page-item"
              :class="{ active: currentPage === page }"
            >
              <button class="page-link" @click="goToPage(page)">{{ page }}</button>
            </li>

            <li class="page-item" :class="{ disabled: currentPage === totalPages }">
              <button class="page-link" @click="nextPage">»</button>
            </li>
          </ul>
        </nav>

      </div>
    </div>

    <!-- Nút thêm -->
    <div class="add-scent-button">
      <button @click="openAddForm" class="btn btn-primary btn-lg">+ Thêm mùi hương</button>
    </div>

    <!-- Popup Form -->
    <div class="modal" v-if="showForm">
      <div class="modal-content">
        <h3>{{ isEdit ? "✏ Cập nhật Mùi Hương" : "➕ Thêm Mùi Hương" }}</h3>

        <div class="form-group">
          <label>Tên mùi:</label>
          <input v-model="form.name" />
        </div>

        <div class="form-group">
          <label>Mô tả:</label>
          <textarea v-model="form.description" rows="3"></textarea>
        </div>

        <div class="modal-actions">
          <button class="btn btn-save" @click="save">Lưu</button>
          <button class="btn btn-cancel" @click="closeForm">Hủy</button>
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
  components: { Sidebar, HeaderAdmin },

  data() {
    return {
      baseURL: "http://127.0.0.1:8000",
      scents: [],
      showForm: false,
      isEdit: false,
      editId: null,
      form: { name: "", description: "" },
      currentPage: 1,
      pageSize: 6,
    };
  },

  computed: {
    totalPages() {
      return Math.ceil(this.scents.length / this.pageSize);
    },
    paginated() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.scents.slice(start, start + this.pageSize);
    },
  },

  mounted() {
    this.load();
  },

  methods: {
    async load() {
      try {
        const res = await axios.get(`${this.baseURL}/api/indexS`);
        this.scents = Array.isArray(res.data) ? res.data : [];
      } catch (err) {
        console.error("Lỗi tải mùi hương:", err);
      }
    },

    openAddForm() {
      this.isEdit = false;
      this.showForm = true;
      this.form = { name: "", description: "" };
    },

    openEditForm(item) {
      this.isEdit = true;
      this.showForm = true;
      this.editId = item.id;
      this.form = { ...item };
    },

    closeForm() {
      this.showForm = false;
    },

    async save() {
      if (!this.form.name.trim()) {
        alert("Tên mùi không được trống!");
        return;
      }

      try {
        if (this.isEdit) {
          await axios.put(`${this.baseURL}/api/updateS/${this.editId}`, this.form);
        } else {
          await axios.post(`${this.baseURL}/api/storeS`, this.form);
        }

        this.closeForm();
        this.load();
      } catch (err) {
        console.error("Lỗi lưu mùi hương:", err);
      }
    },

    async deleteScent(id) {
      if (!confirm("Bạn có chắc muốn xoá?")) return;

      try {
        await axios.delete(`${this.baseURL}/api/destroyS/${id}`);
        this.load();
      } catch (err) {
        console.error("Lỗi xoá mùi:", err);
      }
    },

    nextPage() {
      if (this.currentPage < this.totalPages) this.currentPage++;
    },
    prevPage() {
      if (this.currentPage > 1) this.currentPage--;
    },
    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>

<style scoped>
.header { position: fixed; top:0; left:0; right:0; z-index:100; background:#fff; border-bottom:1px solid #eee; }
.admin-body { display:flex; margin-top:80px; }
.sidebar { position:fixed; top:80px; left:0; width:250px; height:calc(100vh-80px); background:#f9f9f9; border-right:1px solid #ddd; padding:20px; overflow-y:auto; }
.main-content { flex:1; margin-left:270px; padding:20px 40px; background:#fafafa; }
.section-title { font-size:24px; font-weight:bold; margin-bottom:18px; }

.table-container { background:white; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.05); }
.table { width:100%; border-collapse:collapse; table-layout:fixed; }
.table th, .table td { padding:10px 12px; text-align:center; vertical-align:middle; border-bottom:1px solid #ddd; word-wrap:break-word; }
.table th { background:#000; color:white; font-weight:500; }
.table td button { margin:0 2px; }

.btn-outline-primary, .btn-outline-danger { padding:5px 10px; font-size:14px; cursor:pointer; border-radius:6px; }
.btn-outline-primary { border:1px solid #007bff; color:#007bff; }
.btn-outline-danger { border:1px solid #dc3545; color:#dc3545; }

.add-scent-button { position: fixed; bottom:20px; right:20px; }

.pagination-wrap { display:flex; justify-content:center; margin-top:20px; }
.pagination .page-item { margin:0 5px; }
.page-link { border:1px solid #000; padding:5px 10px; cursor:pointer; background:white; }
.page-item.active .page-link { background:#000; color:#fff; }

.modal { position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.4); display:flex; justify-content:center; align-items:center; }
.modal-content { background:white; padding:20px 25px; border-radius:10px; width:420px; max-width:90%; }
.modal-content h3 { margin-bottom:15px; font-size:18px; }
.form-group { margin-bottom:12px; display:flex; flex-direction:column; }
.form-group label { margin-bottom:4px; font-weight:500; }
.form-group input, .form-group textarea { padding:6px 10px; border:1px solid #ccc; border-radius:6px; }

.modal-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:12px; }
.btn-save { background:#000; color:white; padding:8px 14px; border-radius:6px; cursor:pointer; }
.btn-cancel { background:#ccc; padding:8px 14px; border-radius:6px; cursor:pointer; }
</style>
