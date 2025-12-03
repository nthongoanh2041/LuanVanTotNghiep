<template>
  <header class="header">
    <div class="header-container">

      <!-- Logo -->
      <router-link to="/admin/dashboard" class="logo">
        <div class="logo-icon">S</div>
        <span class="logo-text">SCENTS SHOP</span>
      </router-link>


      <!-- User -->
      <div class="actions" v-if="user">
        <span class="welcome-text">
          Xin chào, <strong>{{ user.name }}</strong>
        </span>
         <!-- Notification Bell -->
      <div class="notification-wrapper">
        <div class="bell" @click="toggleDropdown">
          🔔

        </div>

        <!-- Dropdown -->
        <div v-if="showDropdown" class="dropdown">

          <div class="dropdown-header">
            <span>Thông báo</span>
          </div>

          <div v-if="notifications.length === 0" class="empty">Không có thông báo</div>

          <div v-for="noti in notifications" :key="noti.id"
               class="noti-item"
               :class="{ unread: !noti.read_at }"
               @click="markOneAsRead(noti.id)">
            <p>{{ noti.data.message }}</p>
            <small>{{ noti.created_at }}</small>
          </div>
        </div>
      </div>

        <button @click="logout" class="btn-logout">Đăng xuất</button>
      </div>

    </div>
  </header>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";

const router = useRouter();

const user = ref(null);
const notifications = ref([]);
const showDropdown = ref(false);

const unreadCount = ref(0);

const toggleDropdown = () => {
  showDropdown.value = !showDropdown.value;
};

const loadNotifications = async () => {
  const stored = JSON.parse(sessionStorage.getItem("admin_info"));
  if (!stored) return;

  user.value = stored;

  const res = await axios.get(`http://localhost:8000/api/notifications/${stored.id}`);

  notifications.value = res.data;
  unreadCount.value = res.data.filter(n => !n.read_at).length;
};

const markOneAsRead = async (id) => {
  await axios.post(`http://localhost:8000/api/notifications/read/${id}`);
  await loadNotifications();
};

const markAllAsRead = async () => {
  await axios.post(`http://localhost:8000/api/notifications/read-all/${user.value.id}`);
  await loadNotifications();
};

onMounted(() => {
  loadNotifications();
});

const logout = () => {
  sessionStorage.removeItem("admin_info");
  sessionStorage.removeItem("admin_token");
  router.push("/login");
};
</script>

<style scoped>
/* HEADER */
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

/* LOGO */
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
}

/* NOTIFICATION */
.notification-wrapper {
  position: relative;
  margin-right: 20px;
}

.bell {
  position: relative;
  font-size: 22px;
  cursor: pointer;
}

.badge {
  position: absolute;
  top: -5px;
  right: -8px;
  background: red;
  color: white;
  border-radius: 50%;
  padding: 3px 7px;
  font-size: 11px;
}

.dropdown {
  position: absolute;
  right: 0;
  top: 35px;
  width: 320px;
  max-height: 350px;
  overflow-y: auto;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  padding: 10px;
  z-index: 100;
}

.dropdown-header {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
}

.mark-all {
  background: none;
  border: none;
  color: blue;
  cursor: pointer;
  font-size: 13px;
}

.noti-item {
  padding: 10px;
  border-bottom: 1px solid #eee;
  cursor: pointer;
}

.noti-item.unread {
  background: #eef5ff;
  font-weight: 600;
}

.noti-item:hover {
  background: #f5f5f5;
}

.empty {
  text-align: center;
  padding: 20px;
  color: #777;
}

/* USER */
.actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-logout {
  background: transparent;
  border: 1px solid #000;
  color: #000;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
  transition: 0.3s;
}

.btn-logout:hover {
  background: #000;
  color: #fff;
}
</style>
