<template>
  <div class="page-wrapper">
    <Header />

    <div class="container">
      <div v-if="loading" class="loading">Đang tải dữ liệu...</div>

      <div v-else class="product-box">

        <!-- Hình ảnh bên trái -->
        <div class="image-section">
          <img
            :src="getFullImageUrl(product.image)"
            :alt="product.name"
            class="product-image"
          />
        </div>

        <!-- Thông tin sản phẩm bên phải -->
        <div class="info-section">
          <h1 class="title"><strong>Tên sản phẩm: </strong>{{ product.name }}</h1>

          <p class="price"><strong>Giá:</strong>{{ formatCurrency(product.price) }}</p>

          <p class="description"><strong>Mô tả:</strong>
            {{ product.description || "Chưa có mô tả cho sản phẩm này." }}
          </p>

          <div class="info">
            <p><strong>Loại sản phẩm:</strong> {{ product.category?.name || "-" }}</p>
            <p><strong>Mùi hương:</strong> {{ product.scent?.name || "-" }}</p>
          </div>

          <div class="quantity-box">
            <label for="quantity">Số lượng:</label>
            <input id="quantity" type="number" min="1" v-model.number="cartQuantity" />
          </div>

          <div class="action-buttons">
            <button class="add-cart" @click="addToCart(product)">🛒 Thêm vào giỏ hàng</button>
            <router-link to="/dashboard" class="back-btn">⬅ Quay lại</router-link>
          </div>
        </div>
      </div>
    </div>

    <Footer />
  </div>
</template>

<script>
import axios from "axios";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
const DEFAULT_IMAGE_URL = "/no-image.png";

export default {
  name: "ProductDetail",
  components: { Header, Footer },

  data() {
    return {
      product: {},
      cartQuantity: 1,
      loading: true,
    };
  },

  mounted() {
    this.fetchProduct();
  },

  methods: {
    async fetchProduct() {
      const id = this.$route.params.id;

      try {
        const res = await axios.get(`http://localhost:8000/api/showP/${id}`);
        this.product = res.data;
      } catch (err) {
        console.error("Lỗi tải sản phẩm:", err);
        this.product = { image: DEFAULT_IMAGE_URL };
      } finally {
        this.loading = false;
      }
    },

    async addToCart(product) {
      const userInfoStr = localStorage.getItem("user_info");
      let user_id = null;

      if (userInfoStr) {
        user_id = JSON.parse(userInfoStr).id;
      }

      const quantity = this.cartQuantity;
      const total_amount = parseFloat(product.price) * quantity;

      try {
        await axios.post("http://localhost:8000/api/storeC", {
          user_id,
          product_id: product.id,
          quantity,
          total_amount,
        });

        alert(`🛒 Đã thêm ${product.name} (${quantity} cái) vào giỏ hàng!`);
      } catch (err) {
        console.error("❌ Lỗi thêm giỏ hàng:", err);
        alert("Không thể thêm sản phẩm vào giỏ hàng!");
      }
    },

    getFullImageUrl(path) {
      if (!path || path.trim() === "") return DEFAULT_IMAGE_URL;
      return path.trim();
    },

    formatCurrency(value) {
      if (!value) return "Liên hệ";
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },
  },
};
</script>

<style>
.page-wrapper {
  background-color: #ffffff;
  min-height: 100vh;
}

.container {
  width: 90%;
  max-width: 1100px;
  margin: 0 auto;
  padding: 40px 0;
}

.loading {
  text-align: center;
  font-size: 18px;
  color: gray;
}

.product-box {
  display: flex;
  gap: 30px;
  background: white;
  border-radius: 16px;
  padding: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.image-section {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  background: #ffffff;
  border-radius: 12px;
}

.product-image {
  width: 100%;
  max-width: 420px;
  height: 400px;
  object-fit: contain;
  border-radius: 12px;
}

.info-section {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.title {
  font-size: 32px;
  font-weight: bold;
  color: #333;
}

.price {
  font-size: 26px;
  font-weight: bold;
  color: #e55;
}

.description {
  color: #555;
  line-height: 1.6;
}

.info p {
  margin-bottom: 4px;
  color: #444;
}

.quantity-box {
  margin-top: 12px;
}

.quantity-box input {
  width: 80px;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 6px;
  text-align: center;
}

.action-buttons {
  margin-top: 20px;
  display: flex;
  gap: 12px;
}

.add-cart {
  flex: 1;
  padding: 12px;
  background: rgb(0, 0, 0);
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-weight: bold;
}

.add-cart:hover {
  background: #242525;
}

.back-btn {
  flex: 1;
  text-align: center;
  padding: 12px;
  background: #ddd;
  border-radius: 8px;
  text-decoration: none;
  color: black;
}

.back-btn:hover {
  background: #ccc;
}

/* Responsive */
@media (max-width: 900px) {
  .product-box {
    flex-direction: column;
  }
}
</style>
