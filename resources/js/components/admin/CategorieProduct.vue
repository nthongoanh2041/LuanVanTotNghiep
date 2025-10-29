<template>
  <div class="home-view">
    <HeaderAdmin />

    <div class="page">
      <h2 class="page-title">{{ pageTitle }}</h2>

      <div v-if="loading" class="loading">Đang tải sản phẩm...</div>

      <div v-else>
        <div v-if="products.length === 0" class="empty">
          Không có sản phẩm nào thuộc loại này.
        </div>

        <div class="product-list" v-else>
          <div class="product-item" v-for="p in products" :key="p.id">
            <img
              :src="getFullImageUrl(p.image)"
              :alt="p.name"
              class="product-image"
              @error="(e) => e.target.src = defaultImage"
            />

            <div class="product-info">
              <h3 class="product-name">{{ p.name }}</h3>
              <p class="product-price">{{ formatCurrency(p.price) }}</p>
              <p class="product-desc" v-if="p.short_description">{{ p.short_description }}</p>

              <div class="product-actions">
                <router-link :to="`/product/${p.id}`" class="btn btn-detail">
                  Xem chi tiết
                </router-link>
                <button class="btn btn-add" @click="addToCart(p.id)">
                  Thêm vào giỏ
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from "vue";
import { useRoute } from "vue-router";
import HeaderAdmin from "@/components/admin/HeaderAdmin.vue";
import axios from "axios";

// === State ===
const route = useRoute();
const products = ref([]);
const categoryName = ref("");
const loading = ref(true);
const defaultImage = "/default-image.jpg"; // fallback khi thiếu ảnh

// === Lifecycle ===
onMounted(() => {
  fetchProductsByCategory(route.params.id);
});

// Khi chuyển loại từ Header
watch(
  () => route.params.id,
  (newId) => {
    if (newId) fetchProductsByCategory(newId);
  }
);

// === Fetch sản phẩm theo loại ===
const fetchProductsByCategory = async (categoryId) => {
  loading.value = true;
  try {
    const res = await axios.get(`http://localhost:8000/api/category/products/${categoryId}`);

    // Kiểm tra response
    console.log("API trả về:", res.data);

    // Giả định API trả dạng { category_name, products: [...] }
    if (res.data.products) {
      products.value = res.data.products;
      categoryName.value = res.data.category_name || `Loại #${categoryId}`;
    } else {
      // fallback nếu trả trực tiếp danh sách
      products.value = res.data;
      if (res.data.length && res.data[0].category) {
        categoryName.value = res.data[0].category.name;
      } else {
        categoryName.value = `Loại #${categoryId}`;
      }
    }
  } catch (err) {
    console.error("Lỗi khi tải sản phẩm:", err);
    products.value = [];
  } finally {
    loading.value = false;
  }
};

// === Format tiền tệ ===
const formatCurrency = (v) => {
  if (v == null) return "";
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(v);
};

// === Xử lý ảnh ===
const getFullImageUrl = (imagePath) => {
  if (!imagePath) return defaultImage;
  return `http://localhost:8000/${imagePath.replace(/^\/+/, "")}`;
};

// === Tiêu đề trang ===
const pageTitle = computed(() => {
  return categoryName.value
    ? `Sản phẩm - ${categoryName.value}`
    : "Sản phẩm";
});

// === (Tùy chọn) Thêm vào giỏ ===
const addToCart = async (productId) => {
  try {
    const token = localStorage.getItem("token");
    const headers = token ? { Authorization: `Bearer ${token}` } : {};
    await axios.post(
      "http://localhost:8000/api/cart",
      { product_id: productId, qty: 1 },
      { headers }
    );
    alert("Đã thêm vào giỏ hàng!");
  } catch (err) {
    console.error(err);
    alert("Thêm vào giỏ thất bại!");
  }
};
</script>

<style scoped>
.home-view {
  font-family: "Inter", sans-serif;
  background-color: #fafafa;
}

.page {
  max-width: 1100px;
  margin: 24px auto;
  padding: 0 16px;
}

.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  margin-bottom: 20px;
  color: #333;
}

.loading {
  text-align: center;
  font-size: 1.1rem;
  color: #888;
}

.empty {
  text-align: center;
  color: #777;
  font-size: 1rem;
  margin-top: 40px;
}

.product-list {
  display: grid;
  flex-direction: column;
  grid-template-columns: repeat(2, 1fr);
  gap: 16px;


}

.product-item {
  display: flex;
  gap: 16px;
  padding: 12px;
  border: 1px solid #eee;
  border-radius: 12px;
  background: white;
  transition: transform 0.2s;
}

.product-item:hover {
  transform: scale(1.01);
}

.product-image {
  width: 240px;
  height: 240px;
  object-fit: cover;
  border-radius: 8px;
  background: #f9f9f9;
}

.product-info {
  flex: 1;
}

.product-name {
  font-size: 1.1rem;
  margin-bottom: 6px;
  color: #222;
}

.product-price {
  color: #e74c3c;
  font-weight: bold;
  margin-bottom: 8px;
}

.product-desc {
  font-size: 0.9rem;
  color: #555;
  margin-bottom: 12px;
}

.product-actions {
  margin-top: auto; /* đảm bảo luôn nằm cuối */
  display: flex;
  gap: 10px;
}


.btn {
  padding: 6px 12px;
  border-radius: 8px;
  cursor: pointer;
  border: none;
  transition: background 0.2s;
}

.btn-detail {
  background: #000000ff;
  color: white;
}

.btn-add {
  background: #000000ff;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}
</style>
