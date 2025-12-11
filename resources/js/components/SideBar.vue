<template>
  <div class="admin-dashboard">
    <!-- Sidebar -->
    <div class="sidebar">
      <router-link to="/admin/dashboard" class="sidebar-link">
        <i class="fas fa-tachometer-alt"></i> Dashboard
      </router-link>
       <router-link to="/admin/dashboard" class="sidebar-link">
        <i class="fas fa-tachometer-alt"></i> Báo Cáo
      </router-link>
      <router-link to="/admin/users" class="sidebar-link">
        <i class="fas fa-users"></i> Quản lý Người dùng
      </router-link>
      <router-link to="/admin/product-manager" class="sidebar-link">
        <i class="fas fa-box"></i> Quản lý Sản phẩm
      </router-link>
      <router-link to="/admin/manufacturer-manager" class="sidebar-link"> <!-- giống đường dẫn ở router -->
        <i class="fas fa-file-invoice"></i> Quản lý Nhà Sản Xuất
      </router-link>
      <router-link to="/admin/categorie-manager" class="sidebar-link">
        <i class="fas fa-box"></i> Quản lý Loại Sản Phẩm

      </router-link>
      <router-link to="/admin/scent-manager" class="sidebar-link">
        <i class="fas fa-box"></i> Quản lý Mùi Hương
      </router-link>
       <router-link to="/admin/order-manager" class="sidebar-link">
        <i class="fas fa-file-invoice"></i> Quản lý Đơn hàng
      </router-link>

      <router-link to="/admin/stock-manager" class="sidebar-link">
        <i class="fas fa-box"></i> Quản lý Kho
      </router-link>

      <!-- <router-link to="" class="sidebar-link">
        <i class="fas fa-user"></i> Khách hàng thường xuyên
      </router-link> -->



    </div>

  </div>
</template>

<script>
export default {
  name: 'AdminDashboard',
  // Component logic here
};
</script>

<style scoped>
.admin-dashboard {
  display: flex;
  height: 100vh; /* Full height of the viewport */

}

.sidebar {
  width: 280px;
  background-color: #333;
  color: white;
  padding: 20px;
  height: 100%;
  overflow-y: auto; /* Enable scrolling for sidebar content */
  position: fixed; /* Fix the sidebar position */
  left: 0; /* Align sidebar to the left */
  top: 0; /* Align sidebar to the top */
}

.sidebar-link {
  display: block;
  color: white;
  text-decoration: none;
  margin-bottom: 10px;
  transition: background-color 0.3s ease;
  padding: 10px 20px;
}

.sidebar-link:hover {
  background-color: #555;
  border-radius: 4px;
}

.main-content {
  flex: 1;
  padding: 20px;
  background-color: #f0f0f0;
  margin-left: 250px; /* Ensure content doesn't overlap with sidebar */
  overflow-y: auto; /* Enable scrolling for main content */
}
</style>
