<template>
  <div class="category-view">
    <Header />

    <div class="container">
      <h2 class="title">Sản phẩm thuộc loại: {{ categoryName }}</h2>

      <div v-if="loading" class="loading">Đang tải sản phẩm...</div>

      <div v-else>
        <div v-if="products.length === 0" class="no-product">
          Không có sản phẩm nào trong loại này.
        </div>

        <div class="product-grid">
          <div v-for="p in products" :key="p.id" class="product-card">
            <img
              :src="getFullImageUrl(p.image)"
              alt="Ảnh sản phẩm"
              class="product-img"
            />

            <div class="product-info">
              <h3 class="product-name">{{ p.name }}</h3>
              <p class="product-price">{{ formatCurrency(p.price) }}</p>

              <div class="actions">
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
import { ref, onMounted, watch } from "vue";
import { useRoute } from "vue-router";
import Header from "@/components/Header.vue";
import axios from "axios";

const route = useRoute();
const products = ref([]);
const categoryName = ref("");
const loading = ref(true);

// Lấy danh sách sản phẩm theo loại
const fetchProductsByCategory = async (categoryId) => {
  loading.value = true;
  try {
    const res = await axios.get(`http://localhost:8000/api/category/products/${categoryId}`);
    products.value = res.data.products ?? res.data;
    if (res.data.category_name) categoryName.value = res.data.category_name;
    else if (res.data.length && res.data[0]?.category) categoryName.value = res.data[0].category.name;
    else categoryName.value = `Loại #${categoryId}`;
  } catch (err) {
    console.error("Lỗi khi tải sản phẩm:", err);
  } finally {
    loading.value = false;
  }
};

// Khi mount component
onMounted(() => {
  fetchProductsByCategory(route.params.id);
});

// Nếu id trong route thay đổi thì gọi lại API
watch(
  () => route.params.id,
  (newId) => {
    fetchProductsByCategory(newId);
  }
);

const formatCurrency = (v) => {
  if (v == null) return "";
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(v);
};

const getFullImageUrl = (path) =>
  path ? `http://localhost:8000/${path.replace(/^\/+/, "")}` : "/default.jpg";

const addToCart = (id) => {
  alert(`Đã thêm sản phẩm ${id} vào giỏ hàng`);
};
</script>

<style scoped>
.category-view {
  font-family: "Inter", sans-serif;
  background-color: #fafafa;
  min-height: 100vh;
}
.container {
  max-width: 1200px;
  margin: 30px auto;
  padding: 0 20px;
}
.title {
font-size: 22px;
  font-weight: 600;
  margin-bottom: 20px;
}
.loading,
.no-product {
  text-align: center;
  font-size: 16px;
  margin-top: 40px;
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}
.product-card {
  background: #fff;
  border: 1px solid #eee;
  border-radius: 14px;
  overflow: hidden;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  transition: 0.25s;
}
.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
}
.product-img {
  width: 100%;
  height: 250px;
  object-fit: cover;
}
.product-info {
  padding: 12px;
  text-align: left;
}
.product-name {
  font-size: 16px;
  font-weight: 600;
  margin-bottom: 6px;
}
.product-price {
  color: #e67e22;
  font-weight: 600;
  margin-bottom: 10px;
}
.actions {
  display: flex;
  gap: 10px;
  justify-content: flex-start;
}
.btn {
  padding: 6px 10px;
  border: none;
  border-radius: 20px;
  cursor: pointer;
  font-weight: 500;
  transition: 0.25s;
}
.btn-detail {
  background: black;
  color: white;
}
.btn-add {
  background: #e67e22;
  color: white;
}
.btn:hover {
  opacity: 0.9;
}
</style>
