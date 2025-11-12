<template>
  <div class="manufacturer-page">
    <!-- Header -->
    <HeaderAdmin class="header" />

    <div class="admin-body">
      <Sidebar class="sidebar" />

      <div class="main-content">
        <h2 class="section-title"> Quản lý Nhà Sản Xuất</h2>

        <!-- Bảng danh sách -->
        <div class="table-container">
          <table class="table">
            <thead>
              <tr>
                <th>STT</th>
                <th>Tên</th>
                <th>Quốc gia</th>
                <th>Mô tả</th>
                <th>Hành động</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="m in paginated" :key="m.id">
                <td>{{ m.id }}</td>
                <td>{{ m.name }}</td>
                <td>{{ m.country }}</td>
                <td>{{ m.description }}</td>
                <td>
                  <button @click="openEditForm(m)" class="btn btn-outline-primary btn-sm">Sửa</button>
                  <button @click="deleteManufacturer(m.id)" class="btn btn-outline-danger btn-sm">Xoá</button>
                </td>
              </tr>
              <tr v-if="manufacturers.length === 0">
                <td colspan="5" class="text-center">Chưa có nhà sản xuất nào</td>
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
              class="page-item"
              v-for="page in totalPages"
              :key="page"
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

    <!-- Nút thêm mới -->
    <button class="floating-btn" @click="openAddForm">+ Thêm nhà sản xuất</button>

    <!-- Popup Form -->
    <div class="modal" v-if="showForm">
      <div class="modal-content">
        <h3>{{ isEdit ? "✏ Cập nhật Nhà Sản Xuất" : "➕ Thêm Nhà Sản Xuất" }}</h3>

        <div class="form-group">
          <label>Tên:</label>
          <input v-model="form.name" />
        </div>
        <div class="form-group">
          <label>Quốc gia:</label>
          <input v-model="form.country" />
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
      manufacturers: [],
      showForm: false,
      isEdit: false,
      editId: null,
      form: { name: "", country: "", description: "" },
      currentPage: 1,
      pageSize: 6,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.manufacturers.length / this.pageSize);
    },
    paginated() {
      const start = (this.currentPage - 1) * this.pageSize;
      return this.manufacturers.slice(start, start + this.pageSize);
    },
  },
  mounted() {
    this.load();
  },
  methods: {
    async load() {
      try {
        const res = await axios.get(`${this.baseURL}/api/indexM`);
        this.manufacturers = Array.isArray(res.data) ? res.data : res.data.manufacturers || [];
      } catch (err) {
        console.error("Lỗi tải danh sách nhà sản xuất:", err);
      }
    },
    openAddForm() {
      this.isEdit = false;
      this.showForm = true;
      this.form = { name: "", country: "", description: "" };
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
        alert("⚠ Tên nhà sản xuất không được trống!");
        return;
      }
      try {
        if (this.isEdit) {
          await axios.put(`${this.baseURL}/api/updateM/${this.editId}`, this.form);
        } else {
          await axios.post(`${this.baseURL}/api/storeM`, this.form);
        }
        this.closeForm();
        this.load();
      } catch (err) {
        console.error("Lỗi lưu nhà sản xuất:", err);
      }
    },
    async deleteManufacturer(id) {
      if (!confirm("Bạn có chắc muốn xóa?")) return;
      try {
        await axios.delete(`${this.baseURL}/api/destroyM/${id}`);
        this.load();
      } catch (err) {
        console.error("Lỗi xóa nhà sản xuất:", err);
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

.floating-btn { position:fixed; bottom:20px; right:20px; background:#000; color:white; padding:14px 18px; border-radius:50px; border:none; font-size:16px; cursor:pointer; }

.pagination-wrap { display:flex; justify-content:center; margin-top:20px; }
.pagination .page-item { margin:0 5px; }
.page-link { border:1px solid #000; padding:5px 10px; cursor:pointer; background:white; }
.page-item.active .page-link { background:#000; color:#fff; }

.modal { position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.4); display:flex; justify-content:center; align-items:center; }
.modal-content { background:white; padding:20px 25px; border-radius:10px; width:420px; max-width:90%; box-sizing:border-box; }
.modal-content h3 { margin-bottom:15px; font-size:18px; }
.form-group { margin-bottom:12px; display:flex; flex-direction:column; text-align:left; }
.form-group label { margin-bottom:4px; font-weight:500; }
.form-group input, .form-group textarea { padding:6px 10px; border:1px solid #ccc; border-radius:6px; width:100%; box-sizing:border-box; }

.modal-actions { display:flex; justify-content:flex-end; gap:10px; margin-top:10px; }
.btn-save { background:#000; color:white; padding:8px 14px; border:none; cursor:pointer; border-radius:6px; }
.btn-cancel { background:#ccc; padding:8px 14px; cursor:pointer; border-radius:6px; }
</style>
