<template>
  <Header />
  <div class="order-page">
    <h2>🧾 Xác nhận đơn hàng</h2>

    <div class="order-container">
      <!-- 🛍 Giỏ hàng -->
      <div class="cart-summary">
        <h3>Giỏ hàng của bạn</h3>

        <div v-if="cartItems.length > 0">
          <div v-for="item in cartItems" :key="item.id" class="order-item">
            <img
              :src="getFullImageUrl(item.product.image)"
              :alt="item.product.name"
              class="product-image"
            />
            <div class="item-details">
              <p class="name">{{ item.product.name }}</p>
              <p>Số lượng: {{ item.quantity }}</p>
              <p>Giá: {{ formatCurrency(item.product.price) }}</p>
              <p class="total">Tổng: {{ formatCurrency(itemTotal(item)) }}</p>
            </div>
          </div>

          <div class="order-total">
            <strong>Tổng cộng:</strong>
            <span>{{ formatCurrency(totalAmount) }}</span>
          </div>
        </div>
        <p v-else>Giỏ hàng của bạn đang trống.</p>
      </div>

      <!-- 👤 Form thông tin khách hàng -->
      <div class="customer-info">
        <h3>Thông tin khách hàng</h3>

        <form @submit.prevent="submitOrder">
          <div class="form-group">
            <label>Họ và tên:</label>
            <input type="text" v-model="customer.name" required />
          </div>

          <div class="form-group">
            <label>Email:</label>
            <input type="email" v-model="customer.email" required />
          </div>

          <div class="form-group">
            <label>Số điện thoại:</label>
            <input type="text" v-model="customer.phone" required />
          </div>

          <div class="form-group">
            <label>Địa chỉ giao hàng:</label>
            <textarea v-model="customer.address" required></textarea>
          </div>

          <!-- 💳 Phương thức thanh toán -->
          <div class="form-group payment-method">
            <label>Phương thức thanh toán:</label>
            <div class="payment-options">
              <div class="option">
                <span>Thanh toán tiền mặt</span>
                <input
                  type="radio"
                  value="cash"
                  v-model="customer.payment_method"
                  required
                />
              </div>
              <div class="option">
                <span>Chuyển khoản qua ngân hàng</span>
                <input
                  type="radio"
                  value="bank_transfer"
                  v-model="customer.payment_method"
                />
              </div>
            </div>
          </div>

          <button type="submit" class="btn-confirm">Xác nhận đặt hàng</button>
        </form>

        <div class="order-page">

  <!-- ✅ Hiện thông báo khi đặt hàng thành công -->
  <div v-if="orderSuccess" class="success-message">
     Cảm ơn bạn đã đặt hàng!
  </div>
</div>
      </div>
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
      baseURL: "http://127.0.0.1:8000",
      cartItems: [],
      userId: null,
      orderSuccess: false, //  Trạng thái thông báo
      customer: {
        name: "",
        email: "",
        phone: "",
        address: "",
        payment_method: "",
      },
    };
  },
  computed: {
    totalAmount() {
      return this.cartItems.reduce((sum, item) => sum + this.itemTotal(item), 0);
    },
  },
  mounted() {
    this.initUserAndCart();
  },

  methods: {
    async initUserAndCart() {
      const userInfoStr = localStorage.getItem("user_info");
      if (!userInfoStr) {
        console.warn("⚠ Chưa đăng nhập — form trống cho khách tự nhập");
        this.fetchCart();
        return;
      }

      const user = JSON.parse(userInfoStr);
      this.userId = user.id;

      try {
        const res = await axios.get(`${this.baseURL}/api/user/${this.userId}`);
        this.customer = {
          name: res.data.name || "",
          email: res.data.email || "",
          phone: res.data.phone || "",
          address: res.data.address || "",
          payment_method: "",
        };
      } catch (err) {
        console.error("❌ Lỗi khi lấy thông tin user:", err);
      }

      this.fetchCart();
    },

    fetchCart() {
      if (!this.userId) return;
      axios
        .get(`${this.baseURL}/api/indexC/${this.userId}`)
        .then((res) => {
          this.cartItems = res.data.cartItems || [];
        })
        .catch((err) => {
          console.error("❌ Lỗi tải giỏ hàng:", err);
        });
    },

    itemTotal(item) {
      return parseFloat(item.product.price) * parseInt(item.quantity);
    },

    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },

    getFullImageUrl(imagePath) {
      if (!imagePath) return "/no-image.png";
      imagePath = imagePath.trim();
      if (imagePath.startsWith("/")) imagePath = imagePath.substring(1);
      return `${this.baseURL}/${imagePath}`;
    },

    async submitOrder() {
      if (this.cartItems.length === 0) {
        alert("⚠ Giỏ hàng đang trống, không thể đặt hàng!");
        return;
      }

      if (!this.customer.payment_method) {
        alert("⚠ Vui lòng chọn phương thức thanh toán!");
        return;
      }

      const orderData = {
        customer: this.customer,
        cart: this.cartItems,
        total: this.totalAmount,
        user_id: this.userId,
      };

      try {
        const res = await axios.post(`${this.baseURL}/api/storeO`, orderData);
        console.log("✅ Kết quả:", res.data);

        // ✅ Hiện thông báo và đợi 5 giây trước khi quay lại trang giỏ hàng
        this.orderSuccess = true;
        this.cartItems = [];

        setTimeout(() => {
          this.$router.push("/cart");
        }, 5000); // 5 giây

      } catch (err) {
        console.error("❌ Lỗi khi đặt hàng:", err);
        alert("Đặt hàng thất bại, vui lòng thử lại!");
      }
    },
  },
};
</script>




<style scoped>
.order-page {
  max-width: 1100px;
  margin: 20px auto;
  padding: 20px;
}

.order-container {
  display: flex;
  gap: 30px;
  align-items: flex-start;
}

.cart-summary,
.customer-info {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.1);
  flex: 1;
}

h3 {
  margin-bottom: 15px;
  color: #222;
}

.order-item {
  display: flex;
  align-items: center;
  border-bottom: 1px solid #eee;
  padding: 10px 0;
}

.product-image {
  width: 120px;
  height: 120px;
  object-fit: cover;
  margin-right: 15px;
  border-radius: 6px;
}

.item-details {
  flex: 1;
  font-size: 15px;
}

.item-details .name {
  font-weight: bold;
  margin-bottom: 5px;
}

.item-details .total {
  color: #c91414;
  font-weight: bold;
}

.order-total {
  text-align: right;
  margin-top: 15px;
  font-size: 18px;
}

.form-group {
  margin-bottom: 12px;
}

.form-group label {
  display: block;
  font-weight: bold;
  margin-bottom: 5px;
}

input,
textarea {
  width: 100%;
  padding: 8px;
  border-radius: 6px;
  border: 1px solid #ccc;
}

textarea {
  min-height: 70px;
}

/*  Thanh toán */
.payment-method .payment-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.payment-method .payment-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.payment-method .option {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #f9f9f9;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #ddd;
  transition: 0.2s;
}

.payment-method .option:hover {
  background: #f0f0f0;
}

.payment-method .option span {
  font-size: 15px;
  font-weight: 500;
  color: #222;
}

/* căn chỉnh radio button cho thẳng hàng */
.payment-method .option input[type="radio"] {
  width: 18px;
  height: 18px;
  accent-color: #000; /* đổi màu đen cho đẹp */
  margin-left: 10px;
}


.payment-method .option span {
  font-size: 15px;
}

.btn-confirm {
  width: 100%;
  background: #000;
  color: #fff;
  padding: 10px 0;
  border: none;
  border-radius: 6px;
  font-size: 16px;
  cursor: pointer;
  margin-top: 10px;
}

.btn-confirm:hover {
  background: #7d0e0e;
}
.success-message {
  background-color: #e8fbe8;
  color: #0b720b;
  border: 1px solid #8dd88d;
  padding: 15px;
  border-radius: 8px;
  font-size: 16px;
  text-align: center;
  margin: 20px 0;
  animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-5px); }
  to { opacity: 1; transform: translateY(0); }
}

</style>
