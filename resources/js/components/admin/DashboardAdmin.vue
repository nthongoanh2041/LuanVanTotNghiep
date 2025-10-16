<template>
  <div class="admin-layout">
    <!-- Header cố định trên cùng -->
    <HeaderAdmin class="header" />

    <!-- Thân trang chia làm 2 phần: sidebar + nội dung -->
    <div class="admin-body">
      <SideBar class="sidebar" />
    </div>
  </div>
</template>

<script>
import SideBar from "@/components/SideBar.vue";
import HeaderAdmin from "./HeaderAdmin.vue";

export default {
  name: "Dashboard",
  components: {
    SideBar,
    HeaderAdmin,
  },
};
</script>

<style scoped>
/* Toàn layout */
.admin-layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

/* Header cố định */
.header {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 100;
  background: white;
  border-bottom: 1px solid #e5e5e5;
}

/* Phần thân chính */
.admin-body {
  display: flex;
  flex: 1;
  margin-top: 80px; /* cách phần header 1 đoạn để không bị chồng */
}

/* Sidebar */
.sidebar {
  width: 260px;
  background-color: #2f2f2f;
  color: white;
  height: calc(100vh - 80px); /* Trừ chiều cao header */
  position: fixed;
  top: 80px; /* nằm dưới header */
  left: 0;
  padding: 20px;
  overflow-y: auto;
}

/* Nội dung chính */
.main-content {
  margin-left: 260px; /* chừa chỗ cho sidebar */
  padding: 20px;
  background: #fff;
  flex: 1;
  overflow-y: auto;
}
</style>
