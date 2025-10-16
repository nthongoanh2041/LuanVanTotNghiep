<template>
  <header class="header">
    <div class="header-container">
      <!-- Logo -->
       <router-link to="/" class="logo">
        <div class="logo-icon">S</div>
         <span class="logo-text">SCENTS SHOP</span>
        </router-link>

      <!-- Actions -->
      <div class="actions" v-if="user">
        <span class="welcome-text">
          Xin chào, <strong>{{ user.name }}</strong>
        </span>
        <button @click="logout" class="btn-logout">Đăng xuất</button>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();
const user = ref(null);

onMounted(() => {
  const storedUser = localStorage.getItem("user");
  if (storedUser) {
    user.value = JSON.parse(storedUser);
  }
});

const logout = () => {
  localStorage.removeItem("user");
  localStorage.removeItem("token");
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
