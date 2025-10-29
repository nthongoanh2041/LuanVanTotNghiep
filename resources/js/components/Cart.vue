<template>
  <Header />

  <div class="cart-container">
    <h2>🛒 Giỏ hàng của bạn</h2>

    <div v-if="cartItems.length > 0">
      <!-- Danh sách sản phẩm -->
      <div v-for="item in cartItems" :key="item.id" class="cart-item">
        <!-- Ảnh sản phẩm -->
        <img
          :src="getFullImageUrl(item.product.image)"
          :alt="item.product.name"
          class="product-image"
        />

        <!-- Thông tin -->
        <div class="item-details">
          <h3>Tên sản phẩm :{{ item.product.name }}</h3>
          <p class="item-price">Giá: {{ formatCurrency(item.product.price) }}</p>

          <div class="quantity-container">
            <label :for="'quantity-' + item.id">Số lượng:</label>
            <input
              type="number"
              :id="'quantity-' + item.id"
              v-model.number="item.quantity"
              min="1"
              @change="updateCartItem(item)"
            />
          </div>

          <p class="item-total">
            Tổng: <span>{{ formatCurrency(itemTotal(item)) }}</span>
          </p>

          <button class="btn-remove" @click="removeCartItem(item)">Xóa</button>
        </div>
      </div>

      <!-- Tổng tiền -->
      <div class="total-container">
        <p class="total-label">Tổng tiền:</p>
        <p class="total-amount">{{ formatCurrency(totalAmount) }}</p>
      </div>

      <!-- Nút thanh toán -->
      <div class="checkout-container">
        <button class="btn-checkout" @click="checkout">Đặt hàng</button>
      </div>
    </div>

    <!-- Giỏ hàng trống -->
    <div v-else>
      <p class="empty-cart-message">Giỏ hàng của bạn đang trống.</p>
    </div>
  </div>

  <Footer />
</template>

<script>
import axios from "axios";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";

export default {
  components: { Header, Footer },
  data() {
    return {
      cartItems: [],
      baseURL: "http://127.0.0.1:8000",
    };
  },
  computed: {
    totalAmount() {
      return this.cartItems.reduce((sum, item) => sum + this.itemTotal(item), 0);
    },
  },
  mounted() {
    this.fetchCart();
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },

    getFullImageUrl(imagePath) {
      if (!imagePath) return "/no-image.png";
      imagePath = imagePath.trim();

      // Nếu có dấu / ở đầu thì bỏ bớt để tránh lỗi //
      if (imagePath.startsWith("/")) {
        imagePath = imagePath.substring(1);
      }

      return `${this.baseURL}/${imagePath}`;
    },

    itemTotal(item) {
      return parseFloat(item.product.price) * parseInt(item.quantity);
    },

    updateCartItem(item) {
      if (item.quantity <= 0) {
        this.removeCartItem(item);
        return;
      }

      axios
        .put(`${this.baseURL}/api/updateC/${item.id}`, { quantity: item.quantity })
        .then(() => {
          console.log("✅ Cập nhật giỏ hàng thành công");
        })
        .catch((err) => {
          console.error("❌ Lỗi cập nhật:", err);
        });
    },

    removeCartItem(item) {
      axios
        .delete(`${this.baseURL}/api/destroyC/${item.id}`)
        .then(() => {
          this.cartItems = this.cartItems.filter((i) => i.id !== item.id);
        })
        .catch((err) => {
          console.error("❌ Lỗi khi xóa:", err);
        });
    },

    fetchCart() {
      const userInfoStr = localStorage.getItem("user");
      if (!userInfoStr) {
        console.warn("⚠ Không có user_id trong localStorage!");
        return;
      }

      const userInfo = JSON.parse(userInfoStr);
      const user_id = userInfo.id;

      axios
        .get(`${this.baseURL}/api/indexC/${user_id}`)
        .then((res) => {
          if (res.data.cartItems) {
            this.cartItems = res.data.cartItems;
          } else if (Array.isArray(res.data)) {
            this.cartItems = res.data;
          } else {
            this.cartItems = [];
          }
        })
        .catch((err) => {
          console.error("❌ Lỗi tải giỏ hàng:", err);
        });
    },

    checkout() {
      alert("🧾 Tiến hành thanh toán...");
      // this.$router.push("/checkout");
    },
  },
};
</script>

<style scoped>
.cart-container {
  max-width: 900px;
  margin: 20px auto;
  padding: 20px;
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
  text-align: center;
  margin-bottom: 20px;
  color: #333;
}

.cart-item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding: 15px 0;
}

.product-image {
  width: 250px;
  height: 250px;
  object-fit: cover;
  border-radius: 8px;
  margin-right: 20px;
}

.item-details {
  flex: 1;
  font-size: 20px;
}

.item-price {
  color: #2a2a2a;
  margin-bottom: 8px;
}

.quantity-container {

  margin-bottom: 8px;

}

.item-total span {
  font-weight: bold;
  color: #c91414;
}

.btn-remove {
  background: #000;
  color: #fff;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 13px;
}

.btn-remove:hover {
  background: #c82333;
}

.total-container {
  margin-top: 20px;
  text-align: right;
  font-size: 22px;
  font-weight: bold;

}

.total-amount {
  color: #b30b0b;
}

.checkout-container {
  text-align: right;
  margin-top: 20px;

}

.btn-checkout {
  background: #000000;
  color: white;
  padding: 10px 40px;
  border: none;
  border-radius: 6px;
  font-weight: bold;
  cursor: pointer;
  font-size: 15px;
}

.btn-checkout:hover {
  background: #7d0e0e;
}

.empty-cart-message {
  text-align: center;
  color: #555;
  margin-top: 40px;
}
</style>
