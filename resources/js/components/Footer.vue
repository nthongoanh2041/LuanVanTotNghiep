<template>
  <footer class="footer">
    <div class="footer-container">
      <!-- Cột 1: Logo & Intro -->
      <div>
        <h2 class="text-black text-2xl font-semibold mb-4">Scents Shop</h2>
        <p class="text-gray-600 text-sm leading-relaxed">
          Scents Shop mang đến cho bạn những dòng nến thơm và tinh dầu tự nhiên giúp thư giãn, tái tạo năng lượng
          và lan tỏa không gian sống tích cực.
        </p>
      </div>

      <!-- Cột 2: Khám phá & Hỗ trợ -->
      <div class="grid grid-cols-2 gap-8">
        <!-- Khám phá -->
        <div>
          <h3 class="text-black font-semibold mb-4 uppercase text-sm tracking-wide">Khám phá</h3>
          <ul class="space-y-2 text-sm">
            <li><router-link to="/" class="hover:text-black transition">Trang chủ</router-link></li>
            <li><router-link to="/products" class="hover:text-black transition">Sản phẩm</router-link></li>
            <li><router-link to="/about" class="hover:text-black transition">Giới thiệu</router-link></li>
            <li><router-link to="/contact" class="hover:text-black transition">Liên hệ</router-link></li>
          </ul>
        </div>

        <!-- Hỗ trợ -->
        <div>
          <h3 class="text-black font-semibold mb-4 uppercase text-sm tracking-wide">Hỗ trợ</h3>
          <ul class="space-y-2 text-sm">
            <li><a href="#" class="hover:text-black transition">Chính sách đổi trả</a></li>
            <li><a href="#" class="hover:text-black transition">Chính sách bảo mật</a></li>
            <li><a href="#" class="hover:text-black transition">Điều khoản sử dụng</a></li>
          </ul>
        </div>
      </div>

      <!-- Cột 3: Liên hệ -->
      <div>
        <h3 class="text-black font-semibold mb-4 uppercase text-sm tracking-wide">Liên hệ</h3>
        <p class="text-sm text-gray-600 leading-relaxed">
          ✉️ support@Scents Shop.vn
        </p>

        <div class="socials flex gap-4 mt-3">
          <a href="#"><i class="ri-facebook-fill text-lg text-gray-500 hover:text-black transition"></i></a>
          <a href="#"><i class="ri-instagram-line text-lg text-gray-500 hover:text-black transition"></i></a>
          <a href="#"><i class="ri-tiktok-fill text-lg text-gray-500 hover:text-black transition"></i></a>
        </div>
      </div>
    </div>

    <!-- Bản quyền -->
    <div class="footer-bottom">
      © 2025 Scents Shop. All rights reserved.
    </div>
  </footer>
</template>

<script setup>
</script>

<style scoped>
.footer {
  background-color: #ffffff;
  color: #333333;
  padding-top: 3rem;
  padding-bottom: 3rem;
  margin-top: 4rem;
  font-family: "Inter", "Helvetica Neue", Arial, sans-serif;
  border-top: 1px solid #e5e7eb;
}

/* === 3 CỘT CHÍNH === */
.footer-container {
  max-width: 1280px;
  margin: 0 auto;
  padding: 0 1.5rem;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 2.5rem;
}

/* === Tiêu đề và chữ === */
.footer h2 {
  color: #000000;
  font-size: 1.75rem;
  font-weight: 600;
  letter-spacing: -0.02em;
  margin-bottom: 1rem;
}

.footer h3 {
  color: #000000;
  font-weight: 500;
  font-size: 1rem;
  margin-bottom: 1rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.footer p {
  color: #555555;
  font-size: 0.95rem;
  line-height: 1.7;
}

/* === Link style === */
.footer ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer ul li a {
  color: #666666;
  font-size: 0.95rem;
  text-decoration: none;
  transition: color 0.25s ease, transform 0.25s ease;
}

.footer ul li a:hover {
  color: #000000;
  transform: translateX(3px);
}

/* === Socials === */
.footer .socials a {
  color: #555555;
  font-size: 1.25rem;
  transition: color 0.3s ease, transform 0.3s ease;
}

.footer .socials a:hover {
  color: #000000;
  transform: translateY(-2px);
}

/* === Bản quyền dưới cùng === */
.footer-bottom {
  border-top: 1px solid #e5e7eb;
  margin-top: 3rem;
  padding-top: 1rem;
  text-align: center;
  font-size: 0.9rem;
  color: #777777;
  letter-spacing: 0.02em;
}

/* === Responsive === */
@media (max-width: 1024px) {
  .footer-container {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 640px) {
  .footer-container {
    grid-template-columns: 1fr;
    text-align: center;
  }

  .footer h2 {
    font-size: 1.4rem;
  }

  .footer h3 {
    font-size: 0.9rem;
  }

  .footer p,
  .footer ul li a {
    font-size: 0.85rem;
  }

  .footer .socials {
    justify-content: center;
  }
}
</style>
