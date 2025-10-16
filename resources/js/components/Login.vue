<template>
  <Header />
  <div class="login-page">
    <div class="login-card">
      <!-- Logo + Tên thương hiệu -->
      <div class="brand-box">
        <div class="logo-box">S</div>
        <span class="brand-name">Scents Shop</span>
      </div>

      <!-- Title -->
      <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">
        Đăng nhập tài khoản
      </h2>

      <!-- Form -->
      <form @submit.prevent="login" class="space-y-5">
        <!-- Email -->
        <div class="relative">
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="input-style"
            required
          />
          <i class="fa-regular fa-envelope input-icon"></i>
        </div>

        <!-- Password -->
        <div class="relative">
          <input
            v-model="form.password"
            type="password"
            placeholder="Mật khẩu"
            class="input-style"
            required
          />
          <i class="fa-solid fa-lock input-icon"></i>
        </div>

        <!-- Button -->
        <button type="submit" class="btn-submit" :disabled="loading">
          <span v-if="loading"><i class="fas fa-spinner fa-spin"></i> Đang xử lý...</span>
          <span v-else>Đăng nhập</span>
        </button>
      </form>

      <!-- Error / Success -->
      <p v-if="error" class="text-center text-red-500 text-sm mt-4">
        {{ error }}
      </p>
      <p v-if="success" class="text-center text-green-600 text-sm mt-4">
        {{ success }}
      </p>

      <!-- Footer -->
      <div class="text-center mt-6 text-sm text-gray-500">
        Chưa có tài khoản?
        <a href="/register" class="text-black font-semibold hover:underline">
          Đăng ký ngay
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import Header from "@/components/Header.vue";
import { useRouter } from "vue-router";

const router = useRouter();

const form = ref({
  email: "",
  password: "",
});

const error = ref("");
const success = ref("");
const loading = ref(false);

//  kiểm tra email cơ bản
const isValidEmail = (email) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

const login = async () => {
  error.value = "";
  success.value = "";
  loading.value = true;

  // ✅ Kiểm tra lỗi frontend
  if (!form.value.email || !form.value.password) {
    error.value = "Vui lòng nhập đầy đủ email và mật khẩu.";
    loading.value = false;
    return;
  }

  if (!isValidEmail(form.value.email)) {
    error.value = "Email không hợp lệ. Vui lòng nhập đúng định dạng.";
    loading.value = false;
    return;
  }

  if (form.value.password.length < 6) {
    error.value = "Mật khẩu phải có ít nhất 6 ký tự.";
    loading.value = false;
    return;
  }

  // ✅ Nếu hợp lệ, gọi API Laravel
  try {
    const response = await axios.post("http://localhost:8000/api/login", form.value);

    // Laravel trả về: { token: "xxx", user: {...} }
    // Sau khi axios.post("http://localhost:8000/api/login", form.value)
const { user, token } = response.data;

// Lưu thông tin user và token
localStorage.setItem("user", JSON.stringify(user));
localStorage.setItem("token", token);

const userRole = user.role ? user.role.trim().toLowerCase() : "";


// ✅ Kiểm tra vai trò
    if (userRole === "admin") {
      router.push("/admin/dashboard"); // Trang admin
    } else {
      router.push("/dashboard"); // Trang khách hàng
    }

  } catch (err) {
    error.value = err.response?.data?.message || "Sai email hoặc mật khẩu!";
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css";

/* === Layout tổng === */
.login-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(to bottom, #fafafa, #f5f5f5);
  padding: 2rem;
  font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
}

/* === Form chính === */
.login-card {
  background: #ffffff;
  width: 100%;
  max-width: 420px;
  border: 1px solid #e5e7eb;
  border-radius: 1.5rem;
  padding: 3rem;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.login-card:hover {
  box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08);
}

/* === Brand (logo + tên) === */
.brand-box {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 2rem;
  gap: 0.8rem;
}

.logo-box {
  width: 3.3rem;
  height: 3.3rem;
  background: #000;
  color: #fff;
  border-radius: 0.8rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  font-size: 1.5rem;
  box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.brand-name {
  font-size: 1.6rem;
  font-weight: 600;
  color: #111827;
  letter-spacing: 0.3px;
}

/* === Input === */
.input-style {
  width: 100%;
  padding: 0.85rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.75rem;
  font-size: 0.95rem;
  color: #111827;
  transition: all 0.25s ease;
  background-color: #ffffff;
}

.input-style:focus {
  outline: none;
  border-color: #000000;
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
}

/* Icon trong input */
.input-icon {
  position: absolute;
  right: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: #9ca3af;
  pointer-events: none;
}

/* === Nút đăng nhập === */
.btn-submit {
  width: 100%;
  background: #000000;
  color: #ffffff;
  padding: 0.9rem 0;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 0.5px;
  transition: all 0.25s ease;
}

.btn-submit:hover {
  background: #1f1f1f;
}

.btn-submit:active {
  transform: scale(0.98);
}

/* === Responsive === */
@media (max-width: 480px) {
  .login-card {
    padding: 2rem;
  }

  .brand-name {
    font-size: 1.4rem;
  }
}
</style>
