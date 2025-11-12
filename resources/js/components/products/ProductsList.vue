<template>
  <div class="product-list">
    <h2 class="section-title">Sản phẩm</h2>

    <div v-if="products.length > 0" class="card-container">
      <div
        v-for="product in products"
        :key="product.id"
        class="product-card"
      >
        <router-link :to="'/products/' + product.id" class="card-link">
          <img :src="product.image" class="card-img-top" :alt="product.name" />
        </router-link>

        <h5 class="product-name">{{ product.name }}</h5>
        <p class="product-price">{{ formatCurrency(product.price) }}</p>

        <div class="btn-group">
          <router-link
            :to="'/products/' + product.id + '/show'"
            class="btn btn-outline-secondary btn-sm"
          >
            Xem chi tiết
          </router-link>

          <button
            @click="addToCart(product)"
            class="btn-add-cart"
          >
            Thêm vào giỏ hàng
          </button>
        </div>
      </div>
    </div>

    <div v-else>
      <p>Không có sản phẩm nổi bật để hiển thị.</p>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ProductList",
  props: {
    products: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      cartQuantity: 1, // Số lượng mặc định thêm vào giỏ
    };
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },

   async addToCart(product) {
  const userInfoStr = localStorage.getItem('user_info');
  let user_id = null;

  if (userInfoStr) {
    const userInfo = JSON.parse(userInfoStr);
    user_id = userInfo.id;
  }

  const quantity = this.cartQuantity; // số lượng mặc định
  const total_amount = product.price * quantity; //  Tính tổng tiền

  try {
    const res = await axios.post("http://localhost:8000/api/storeC", {
      user_id: user_id,
      product_id: product.id,
      quantity: quantity,
      total_amount: total_amount, //  Gửi thêm total_amount
    });

    console.log("✅ Thêm sản phẩm vào giỏ hàng:", res.data);
    alert(`🛒 Đã thêm ${product.name} (${quantity} cái) vào giỏ hàng!`);
  } catch (err) {
    console.error("❌ Lỗi khi thêm giỏ hàng:", err);
    alert("Không thể thêm sản phẩm vào giỏ hàng!");
  }
},

  },
};
</script>

<style scoped>
.product-list {
  padding: 100px 80px;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
  text-align: center;
}

/* GRID hiển thị sản phẩm */
.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
}

/* CARD */
.product-card {
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 12px;
  text-align: center;
  padding: 16px;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.product-card img {
  width: 100%;
  height: 270px;
  object-fit: cover;
  border-radius: 10px;
  margin-bottom: 12px;
}

.product-name {
  font-size: 18px;
  font-weight: 600;
  margin-bottom: 6px;
}

.product-price {
  color: #030303;
  margin-bottom: 12px;
}

/* Nhóm nút */
.btn-group {
  display: flex;
  justify-content: center;
  gap: 10px;
}

/* Nút thêm giỏ hàng */
.btn-add-cart {
  background-color: #000000;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
  transition: background 0.2s ease;
}

.btn-add-cart:hover {
  background-color: #9b9797;
}
</style>
