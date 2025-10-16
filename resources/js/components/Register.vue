<template>
  <Header />
  <div class="register-page">
    <div class="register-card">
      <!-- Logo + Thương hiệu -->
      <div class="brand-box">
        <div class="logo-box">S</div>
        <span class="brand-name">Scents Shop</span>
      </div>

      <!-- Tiêu đề -->
      <h2 class="form-title">Đăng ký tài khoản</h2>

      <!-- Form đăng ký -->
      <form @submit.prevent="register" class="space-y-5" novalidate>
        <!-- Họ tên -->
        <div>
          <input
            v-model="form.name"
            type="text"
            placeholder="Họ và tên"
            class="input-style"
            :class="{ 'input-error': errors.name }"
            @input="clearFieldError('name')"
            required
          />
          <p v-if="errors.name" class="error-text">{{ errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <input
            v-model="form.email"
            type="email"
            placeholder="Email"
            class="input-style"
            :class="{ 'input-error': errors.email }"
            @input="validateEmailRealtime"
            required
          />
          <p v-if="errors.email" class="error-text">{{ errors.email }}</p>
        </div>

        <!-- Mật khẩu -->
        <div>
          <input
            v-model="form.password"
            type="password"
            placeholder="Mật khẩu"
            class="input-style"
            :class="{ 'input-error': errors.password }"
            @input="clearFieldError('password')"
            required
          />
          <p v-if="errors.password" class="error-text">{{ errors.password }}</p>
        </div>

        <!-- Nhập lại mật khẩu -->
        <div>
          <input
            v-model="form.password_confirmation"
            type="password"
            placeholder="Nhập lại mật khẩu"
            class="input-style"
            :class="{ 'input-error': errors.password_confirmation }"
            @input="clearFieldError('password_confirmation')"
            required
          />
          <p v-if="errors.password_confirmation" class="error-text">
            {{ errors.password_confirmation }}
          </p>
        </div>

        <!-- Button -->
        <button type="submit" class="btn-submit" :disabled="loading">
          <span v-if="loading"><i class="fas fa-spinner fa-spin mr-2"></i>Đang xử lý...</span>
          <span v-else>Đăng ký</span>
        </button>
      </form>

      <!-- Thông báo -->
      <p v-if="success" class="text-center text-green-600 text-sm mt-4">
        {{ success }}
      </p>
      <p v-if="error" class="text-center text-red-500 text-sm mt-4">
        {{ error }}
      </p>

      <!-- Footer -->
      <div class="text-center mt-8 text-sm text-gray-600">
        Đã có tài khoản?
        <a href="/login" class="text-black font-medium hover:underline">
          Đăng nhập
        </a>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "axios";
import Header from "@/components/Header.vue"; 

const form = ref({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const errors = ref({});
const error = ref("");
const success = ref("");
const loading = ref(false);

const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

// Xóa lỗi khi user nhập lại
function clearFieldError(field) {
  if (errors.value[field]) delete errors.value[field];
  if (error.value) error.value = "";
}

// Kiểm tra email realtime
function validateEmailRealtime() {
  const email = form.value.email.trim();
  if (!email) {
    errors.value.email = "Vui lòng nhập email.";
  } else if (!emailRegex.test(email)) {
    errors.value.email = "Email không hợp lệ.";
  } else {
    delete errors.value.email;
  }
}

// Validate toàn bộ trước khi gửi
const validateBeforeSubmit = () => {
  errors.value = {};
  error.value = "";
  success.value = "";

  if (!form.value.name || !form.value.name.trim()) {
    errors.value.name = "Vui lòng nhập họ và tên.";
  }

  const email = form.value.email.trim();
  if (!email) {
    errors.value.email = "Vui lòng nhập email.";
  } else if (!emailRegex.test(email)) {
    errors.value.email = "Email không hợp lệ.";
  }

  if (!form.value.password) {
    errors.value.password = "Vui lòng nhập mật khẩu.";
  } else if (form.value.password.length < 6) {
    errors.value.password = "Mật khẩu phải có ít nhất 6 ký tự.";
  }

  if (!form.value.password_confirmation) {
    errors.value.password_confirmation = "Vui lòng nhập lại mật khẩu.";
  } else if (form.value.password !== form.value.password_confirmation) {
    errors.value.password_confirmation = "Mật khẩu xác nhận không khớp.";
  }

  return Object.keys(errors.value).length === 0;
};

const register = async () => {
  if (!validateBeforeSubmit()) return;

  loading.value = true;
  error.value = "";
  success.value = "";

  try {
    const resp = await axios.post("http://localhost:8000/api/register", form.value);
    success.value = "🎉 Đăng ký thành công!";
    form.value = { name: "", email: "", password: "", password_confirmation: "" };
    errors.value = {};
  } catch (err) {
    if (err.response?.status === 422) {
      const serverErrors = err.response.data.errors || {};
      for (const key in serverErrors) {
        errors.value[key] = Array.isArray(serverErrors[key])
          ? serverErrors[key][0]
          : serverErrors[key];
      }
      if (!errors.value.email && err.response.data.message) {
        const msg = err.response.data.message.toLowerCase();
        if (msg.includes("email") && (msg.includes("taken") || msg.includes("đã"))) {
          errors.value.email = err.response.data.message;
        }
      }
    } else {
      error.value = err.response?.data?.message || "Đăng ký thất bại! Vui lòng thử lại.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css";

.register-page {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(to bottom, #fafafa, #f5f5f5);
  padding: 2rem;
  font-family: "Inter", sans-serif;
}

.register-card {
  background: #fff;
  max-width: 440px;
  width: 100%;
  border-radius: 1.5rem;
  padding: 3rem;
  border: 1px solid #e5e7eb;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  transition: 0.3s;
}

.register-card:hover {
  box-shadow: 0 14px 30px rgba(0, 0, 0, 0.08);
}

.brand-box {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.8rem;
  margin-bottom: 2rem;
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
}

.brand-name {
  font-size: 1.7rem;
  font-weight: 600;
  color: #111827;
}

.form-title {
  text-align: center;
  font-weight: 700;
  margin-bottom: 2rem;
}

.input-style {
  width: 100%;
  padding: 0.9rem 1rem;
  border: 1px solid #d1d5db;
  border-radius: 0.75rem;
  transition: 0.2s;
}

.input-style:focus {
  border-color: #000;
  box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
}

.input-error {
  border-color: #ef4444 !important;
  box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
}

.error-text {
  color: #ef4444;
  font-size: 0.8rem;
  margin-top: 6px;
}

.btn-submit {
  width: 100%;
  background: #000;
  color: #fff;
  padding: 0.9rem;
  border-radius: 0.75rem;
  font-weight: 600;
  transition: 0.2s;
}

.btn-submit:hover {
  background: #1f1f1f;
}
</style>
