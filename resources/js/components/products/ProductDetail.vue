<template>
  <div class="min-h-screen bg-gray-50">
    <Header />

    <div class="container mx-auto py-12 px-6">
      <div v-if="loading" class="text-center text-gray-500 text-lg">
        Đang tải dữ liệu...
      </div>

      <div
        v-else
        class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-2xl shadow-2xl p-10 border border-gray-200"
      >
        <!-- Hình ảnh sản phẩm -->
        <div class="flex justify-center items-center h-[500px]">
          <img
            :src="product.image || '/no-image.png'"
            :alt="product.name"
            class="rounded-2xl shadow-md max-h-full w-auto object-contain bg-gray-50 p-4"
          />
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="flex flex-col justify-between">
          <div>
            <h2 class="text-4xl font-semibold text-gray-800 mb-4">
              <p><strong>Tên sản phẩm: </strong>{{ product.name }}</p>
            </h2>
            <p class="text-2xl text-red-500 font-semibold mb-4">
              <p><strong>Giá: </strong>{{ formatCurrency(product.price) }}</p>
            </p>
            <p class="text-gray-700 mb-4 leading-relaxed">
             <p><strong>Mô tả sản phẩm: </strong> {{ product.description || "Chưa có mô tả cho sản phẩm này." }}</p>
            </p>

            <div class="space-y-2 text-gray-700">
              <p><strong>Loại sản phẩm:</strong> {{ product.category?.name || "Không có dữ liệu" }}</p>
              <p><strong>Mùi hương:</strong> {{ product.scent?.name || "Không có dữ liệu" }}</p>
              <!-- <p><strong>Số lượng còn lại:</strong> {{ product.quantity }}</p> -->
            </div>

            <div class="mt-6 flex items-center gap-4">
              <label for="quantity" class="font-medium text-gray-700">Số lượng:</label>
              <input
                id="quantity"
                type="number"
                min="1"
                v-model.number="cartQuantity"
                class="border border-gray-300 rounded-lg px-3 py-2 w-24 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
            </div>
          </div>

          <div class="mt-8 flex gap-4">
            <button
  @click="addToCart(product)"
  class="flex-1 bg-blue-600 text-white py-4 rounded-xl text-lg font-semibold hover:bg-blue-700 transition-all shadow-md"
>
  🛒 Thêm vào giỏ hàng
</button>
            <router-link
              to="/dashboard"
              class="flex-1 text-center bg-gray-200 hover:bg-gray-300 text-gray-700 py-4 rounded-xl text-lg font-medium transition-all shadow-sm"
            >
              ⬅ Quay lại
            </router-link>
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
        console.error("Lỗi khi tải chi tiết sản phẩm:", err);
        alert("Không thể tải chi tiết sản phẩm!");
        this.product = { image: "/no-image.png" };
      } finally {
        this.loading = false;
      }
    },

    async addToCart(product) {
  const userInfoStr = localStorage.getItem('user');
  let user_id = null;

  if (userInfoStr) {
    const userInfo = JSON.parse(userInfoStr);
    user_id = userInfo.id;
  }

  const quantity = this.cartQuantity; // số lượng mặc định
  const total_amount = product.price * quantity; // ✅ Tính tổng tiền

  try {
    const res = await axios.post("http://localhost:8000/api/storeC", {
      user_id: user_id,
      product_id: product.id,
      quantity: quantity,
      total_amount: total_amount, // ✅ Gửi thêm total_amount
    });

    console.log("✅ Thêm sản phẩm vào giỏ hàng:", res.data);
    alert(`🛒 Đã thêm ${product.name} (${quantity} cái) vào giỏ hàng!`);
  } catch (err) {
    console.error("❌ Lỗi khi thêm giỏ hàng:", err);
    alert("Không thể thêm sản phẩm vào giỏ hàng!");
  }
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

<style scoped>
.container {
  max-width: 1100px;
}
.container {
  max-width: 1100px;
}

/* Tổng thể */
.min-h-screen {
  background-color: #f9fafb; /* màu nền nhẹ */
}

/* Thẻ chứa chi tiết sản phẩm */
.grid {
  transition: all 0.3s ease;
}

/* Hình ảnh sản phẩm */
img {
  border-radius: 16px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

img:hover {
  transform: scale(1.05);
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
}

/* Tiêu đề sản phẩm */
h2 {
  font-family: 'Inter', sans-serif;
  font-size: 2.2rem;
  font-weight: 700;
  color: #1f2937;
  letter-spacing: 0.5px;
}

/* Giá sản phẩm */
.text-red-500 {
  font-size: 1.8rem;
  font-weight: 600;
  color: #e11d48; /* đỏ đậm */
}

/* Mô tả sản phẩm */
.text-gray-700 {
  color: #374151;
  line-height: 1.6;
}

/* Thông tin phụ */
.space-y-2 p {
  margin-bottom: 6px;
  font-size: 15px;
}

/* Input số lượng */
input[type="number"] {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  width: 70px;
  padding: 8px 10px;
  text-align: center;
  font-size: 15px;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

input[type="number"]:focus {
  outline: none;
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
}

/* Nút thêm vào giỏ hàng */
button.bg-blue-600 {
  background-color: #000000;
  color: #fff;
  padding: 14px 0;
  border-radius: 12px;
  font-size: 17px;
  font-weight: 600;
  transition: all 0.3s ease;
}

button.bg-blue-600:hover {
  background-color: #4c4d50;
  transform: translateY(-2px);
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);
}

/* Nút quay lại */
a.bg-gray-200 {
  background-color: #e5e7eb;
  color: #374151;
  padding: 14px 0;
  border-radius: 12px;
  font-size: 17px;
  font-weight: 500;
  transition: all 0.3s ease;
  text-align: center;
}

a.bg-gray-200:hover {
  background-color: #d1d5db;
  transform: translateY(-2px);
}

/* Bóng và viền thẻ chi tiết */
.grid.bg-white {
  border-radius: 20px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
  transition: box-shadow 0.3s ease;
}

.grid.bg-white:hover {
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

/* Responsive */
@media (max-width: 768px) {
  .grid {
    grid-template-columns: 1fr;
  }
  img {
    max-width: 90%;
  }
  h2 {
    font-size: 1.8rem;
  }
  .text-red-500 {
    font-size: 1.5rem;
  }
}

</style>

