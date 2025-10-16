<template>
  <div class="home-view">
    <Header />

    <div class="page">
      <!-- <h2>{{ pageTitle }}</h2> -->


      <div v-if="loading">Đang tải...</div>
      <div v-else>
        <div v-if="products.length === 0">Không có sản phẩm nào thuộc mùi hương này.</div>

        <div class="product-list">
          <div class="product-item" v-for="p in products" :key="p.id">
            <img
              :src="getFullImageUrl(p.image) || defaultImage"
              alt="p.name"
              class="product-image"
            />

            <div class="product-info">
              <h3 class="product-name">{{ p.name }}</h3>
              <p class="product-price">{{ formatCurrency(p.price) }}</p>
              <p class="product-desc" v-if="p.short_description">{{ p.short_description }}</p>

              <div class="product-actions">
                <router-link :to="`/product/${p.id}`" class="btn btn-detail">Xem chi tiết</router-link>
                <button class="btn btn-add" @click="addToCart(p.id)">Thêm vào giỏ</button>
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
import Header from "@/components/Header.vue";
import axios from "axios";

const route = useRoute();
const products = ref([]);
const scentName = ref("");
const loading = ref(true);
const defaultImage = "/default-image.jpg";

// Khi vào trang
onMounted(() => {
  fetchProductsByScent(route.params.id);
});

// Khi đổi ID (mùi hương khác)
watch(
  () => route.params.id,
  (newId) => {
    fetchProductsByScent(newId);
  }
);

// Gọi API lấy sản phẩm theo mùi hương
const fetchProductsByScent = async (scentId) => {
  loading.value = true;
  try {
    const res = await axios.get(`http://localhost:8000/api/scent/products/${scentId}`);
    products.value = res.data.products ?? res.data;

    if (res.data.scent_name) scentName.value = res.data.scent_name;
    else if (res.data.length && res.data[0]?.scent) scentName.value = res.data[0].scent.name;
    else scentName.value = `ID: ${scentId}`;
  } catch (err) {
    console.error(err);
  } finally {
    loading.value = false;
  }
};

// Tiêu đề trang
const pageTitle = computed(() => {
  return scentName.value
    ? `Sản phẩm có mùi hương - ${scentName.value}`
    : "Sản phẩm theo mùi hương";
});

const formatCurrency = (v) => {
  if (v == null) return "";
  return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(v);
};

const getFullImageUrl = (imagePath) => {
  if (!imagePath) return "/default-image.jpg";
  return `http://localhost:8000/${imagePath.replace(/^\/+/, "")}`;
};

const addToCart = (id) => {
  alert(`Đã thêm sản phẩm ID ${id} vào giỏ hàng!`);
};
</script>

<style scoped>
.home-view {
  font-family: 'Inter', sans-serif;
  background-color: #fafafa;
}
.page {
  max-width: 1100px;
  margin: 24px auto;
  padding: 0 16px;
}
.product-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}
.product-item {
  display: flex;
  gap: 16px;
  padding: 12px;
  border: 1px solid #eee;
  border-radius: 8px;
  align-items: center;
  background: #fff;
}
.product-image {
  width: 120px;
  height: 120px;
  object-fit: cover;
  border-radius: 8px;
}
.product-info {
  flex: 1;
}
.product-name {
  margin: 0 0 6px;
  font-size: 18px;
  font-weight: 600;
}
.product-price {
  margin: 0 0 8px;
  font-weight: 700;
  color: #111;
}
.product-desc {
  margin: 0 0 12px;
  color: #666;
  font-size: 14px;
}
.product-actions {
  display: flex;
  gap: 10px;
}
.btn {
  padding: 8px 12px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  text-decoration: none;
}
.btn-detail {
  background: transparent;
  border: 1px solid #111;
  color: #111;
}
.btn-add {
  background: #111;
  color: #fff;
  border: none;
}
</style>
