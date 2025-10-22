<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
    <Header />

    <div class="container mx-auto py-12 px-6">
      <div v-if="loading" class="text-center text-gray-500 text-lg">Đang tải dữ liệu...</div>

      <div
        v-else
        class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-2xl shadow-2xl p-10 border border-gray-200"
      >
        <!-- Hình ảnh sản phẩm -->
        <div class="flex justify-center items-center">
          <img
            :src="product.image"
            alt="Product Image"
            class="rounded-2xl shadow-md max-h-[500px] w-full object-contain bg-gray-50 p-4"
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
              <p><strong> Số lượng còn lại:</strong> {{ product.quantity }}</p>
            </div>
          </div>

          <!-- Nút thêm vào giỏ hàng -->
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
      baseURL: "http://localhost:8000",
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
        const res = await axios.get(`${this.baseURL}/api/showP/${id}`);
        const p = res.data;

        // Chuẩn hoá đường dẫn ảnh
        let imagePath = p.image ? p.image.replace(/[\r\n\s]+/g, "").trim() : null;
        if (imagePath && !imagePath.startsWith("http")) {
          imagePath = `${this.baseURL}${imagePath.startsWith("/") ? "" : "/"}${imagePath}`;
        }
        this.product = { ...p, image: imagePath || "/no-image.png" };
      } catch (err) {
        console.error("Lỗi khi tải chi tiết sản phẩm:", err);
        alert("Không thể tải chi tiết sản phẩm!");
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
