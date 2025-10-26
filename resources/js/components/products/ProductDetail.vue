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
            <h2 class="text-4xl font-bold text-gray-800 mb-4">{{ product.name }}</h2>
            <p class="text-2xl text-red-500 font-semibold mb-4">
              {{ formatCurrency(product.price) }}
            </p>
            <p class="text-gray-700 mb-4 leading-relaxed">
              {{ product.description || "Chưa có mô tả cho sản phẩm này." }}
            </p>

            <div class="space-y-2 text-gray-700">
              <p><strong>Loại sản phẩm:</strong> {{ product.category?.name || "Không có dữ liệu" }}</p>
              <p><strong>Mùi hương:</strong> {{ product.scent?.name || "Không có dữ liệu" }}</p>
              <p><strong>Số lượng còn lại:</strong> {{ product.quantity }}</p>
            </div>
          </div>

          <div class="mt-8 flex gap-4">
            <button
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
        // image từ backend đã là URL hợp lệ (public/image)
        this.product = res.data;
      } catch (err) {
        console.error("Lỗi khi tải chi tiết sản phẩm:", err);
        alert("Không thể tải chi tiết sản phẩm!");
        this.product = { image: "/no-image.png" }; // fallback
      } finally {
        this.loading = false;
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
</style>
