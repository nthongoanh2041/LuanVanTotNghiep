<template>
  <header class="header">
    <div class="header-container">
      <!-- Logo -->
      <router-link to="/" class="logo">
        <div class="logo-icon">S</div>
        <span class="logo-text">SCENTS SHOP</span>
      </router-link>

      <!-- Navigation -->
      <nav class="nav">
          <!-- Navigation -->
      <div class="dropdown-wrapper"
     @mouseenter="openScentMenu"
     @mouseleave="closeScentMenu">
  <span class="nav-item">Mùi hương</span>
  <ul v-if="showScentDropdown" class="dropdown">
    <li v-for="s in scents" :key="s.id" @click="goToScent(s.id)">
      {{ s.name }}
    </li>
  </ul>
</div>

        <!-- Sản phẩm (dropdown) -->
        <!-- Sản phẩm (dropdown) -->
<div class="dropdown-wrapper"
     @mouseenter="openCategoryMenu"
     @mouseleave="closeCategoryMenu">
  <span class="nav-item">Sản phẩm</span>
  <ul v-if="showCategoryDropdown" class="dropdown">
    <li v-for="cat in categories" :key="cat.id" @click="goToCategory(cat.id)">
      {{ cat.name }}
    </li>
  </ul>
</div>

        <router-link to="/about">Giới thiệu</router-link>
        <router-link to="/contact">Liên hệ</router-link>
      </nav>

      <!-- Actions -->
      <div class="actions">
        <template v-if="user">
          <span class="welcome-text">
            Xin chào, <strong>{{ user.name }}</strong>
          </span>
          <button @click="logout" class="btn-logout">Đăng xuất</button>
        </template>

        <template v-else>
          <router-link to="/login" class="btn-login">Đăng nhập</router-link>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";

const router = useRouter();
const user = ref(null);
const categories = ref([]);
const scents = ref([]);
const showScentDropdown = ref(false);
const showCategoryDropdown = ref(false)
let hoverTimeout = null

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) user.value = JSON.parse(storedUser);
  fetchCategories();
  fetchScents();
});

const fetchCategories = async () => {
  try {
    const res = await axios.get("http://localhost:8000/api/index");
    categories.value = res.data;
  } catch (err) {
    console.error("Lỗi lấy categories:", err);
  }
};

const fetchScents = async () => {
  try {
    const res = await axios.get("http://localhost:8000/api/indexS");
    scents.value = res.data;
  } catch (err) {
    console.error("Lỗi lấy scents:", err);
  }
};

const openScentMenu = () => {
  clearTimeout(hoverTimeout)
  showScentDropdown.value = true
}

const closeScentMenu = () => {
  hoverTimeout = setTimeout(() => {
    showScentDropdown.value = false
  }, 20) // Delay 100ms để tránh mất hover khi rê chuột
}
const openCategoryMenu = () => {
  clearTimeout(hoverTimeout)
  showCategoryDropdown.value = true
}

const closeCategoryMenu = () => {
  hoverTimeout = setTimeout(() => {
    showCategoryDropdown.value = false
  }, 20) // Delay 200ms để tránh mất hover khi rê chuột
}
const goToCategory = (id) => {
  router.push(`/category/products/${id}`)
}

const goToScent = (id) => {
  router.push(`/products/scent/${id}`);
  showScentDropdown.value = false;
};

const logout = () => {
localStorage.removeItem("user");
  localStorage.removeItem("token");
  user.value = null;
  router.push("/login");
};
</script>

<style scoped>
.header {
  position: sticky;
  top: 0;
  background: #fff;
  border-bottom: 1px solid #eee;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.05);
  z-index: 50;
}

.header-container {
  max-width: 1200px;
  margin: 0 auto;
  height: 70px;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* Logo */
.logo {
  display: flex;
  align-items: center;
  text-decoration: none;
}

.logo-icon {
  background: #000;
  color: #fff;
  font-weight: bold;
  font-size: 18px;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 6px;
  margin-right: 10px;
}

.logo-text {
  color: #111;
  font-weight: 700;
  font-size: 20px;
  letter-spacing: 0.5px;
}

/* Nav */
.nav {
  display: flex;
  gap: 30px;
  align-items: center;
}

.nav a, .nav .nav-item {
  color: #444;
  font-weight: 500;
  font-size: 16px;
  text-decoration: none;
  position: relative;
  padding-bottom: 2px;
  transition: color 0.3s ease;
}

.nav a:hover, .nav .nav-item:hover {
  color: #000;
}

.dropdown-wrapper {
  position: relative;
  cursor: pointer;
}

/* dropdown (simple list) */
.dropdown-wrapper {
  position: relative;
  padding-bottom: 10px; /* mở rộng vùng hover */
}

.nav-item {
  display: inline-block;
  padding: 10px 15px;
  cursor: pointer;
}

.dropdown {
  position: absolute;
  top: 100%;
  left: 0;
  background: white;
  border: 1px solid #eee;
  z-index: 9999;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  min-width: 160px;
  padding: 5px 0;
  list-style: none;
}

.dropdown li {
  padding: 8px 15px;
  cursor: pointer;
  white-space: nowrap;
}

.dropdown li:hover {
  background-color: #f1f1f1;
}

/* Actions */
.actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.welcome-text {
  font-size: 15px;
  color: #333;
}

/* Buttons */
.btn-login {
  background: #000;
  color: #fff;
  padding: 10px 22px;
  border-radius: 8px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}
.btn-login:hover {
  background: #222;
  transform: translateY(-1px);
}

.btn-logout {
  background: transparent;
  border: 1px solid #000;
  color: #000;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
  transition: all 0.3s ease;
}
.btn-logout:hover {
  background: #000;
  color: #fff;
}
</style>
