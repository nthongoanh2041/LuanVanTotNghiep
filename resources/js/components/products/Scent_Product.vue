<template>
  <div class="scent-view">
    <Header />

    <div class="container">
      <h2 class="title">Sản phẩm thuộc mùi hương: {{ scentName }}</h2>

      <div v-if="loading" class="loading">Đang tải sản phẩm...</div>

      <div v-else>
        <div v-if="products.length === 0" class="no-product">
          Không có sản phẩm nào thuộc mùi hương này.
        </div>

        <div class="product-grid">
          <div v-for="p in products" :key="p.id" class="product-card">
            <img
              :src="getFullImageUrl(p.image)"
              alt="Ảnh sản phẩm"
              class="product-img"
              @error="onImageError"
            />

            <div class="product-info">
              <h3 class="product-name">{{ p.name }}</h3>
              <p class="product-price">{{ formatCurrency(p.price) }}</p>

              <div class="actions">
                <router-link :to="`/products/${p.id}/show`" class="btn btn-detail">
                  Xem chi tiết
                </router-link>
                <button
                  @click="addToCart(p)"
                  class="btn btn-add"
                >
                  Thêm vào giỏ hàng
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
const scentName = ref("");
const loading = ref(true);
const defaultImage = "/images/no-image.png";

// Gọi API lấy sản phẩm theo mùi hương
const fetchProductsByScent = async (scentId) => {
  loading.value = true;
  try {
    const res = await axios.get(`http://localhost:8000/api/scent/products/${scentId}`);
    products.value = res.data.products ?? res.data;
    if (res.data.scent_name) scentName.value = res.data.scent_name;
    else if (res.data.length && res.data[0]?.scent) scentName.value = res.data[0].scent.name;
    else scentName.value = `Mùi hương #${scentId}`;
  } catch (err) {
    console.error("Lỗi khi tải sản phẩm theo mùi hương:", err);
  } finally {
    loading.value = false;
  }
};

// Gọi khi mount
onMounted(() => {
  fetchProductsByScent(route.params.id);
});

// Khi thay đổi ID mùi trong route
watch(
  () => route.params.id,
  (newId) => {
    fetchProductsByScent(newId);
  }
);

const formatCurrency = (v) => {
  if (v == null) return "";
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(v);
};

const getFullImageUrl = (path) => {
  if (!path) return defaultImage;
  if (path.startsWith("http")) return path;
  return `http://localhost:8000/${path.replace(/^\/+/, "")}`;
};

const onImageError = (e) => {
  e.target.src = defaultImage;
};

const addToCart = async (p) => {
  const userInfoStr = localStorage.getItem('user');
  let user_id = null;

  if (userInfoStr) {
    const userInfo = JSON.parse(userInfoStr);
    user_id = userInfo.id;
  }

  const quantity = 1; // hoặc bạn có thể dùng ref nếu muốn chọn số lượng
  const total_amount = p.price * quantity; // ✅ Tính tổng tiền

  try {
    const res = await axios.post("http://localhost:8000/api/storeC", {
      user_id: user_id,
      product_id: p.id,
      quantity: quantity,
      total_amount: total_amount,
    });

    console.log("✅ Thêm sản phẩm vào giỏ hàng:", res.data);
    alert(`🛒 Đã thêm ${p.name} (${quantity} cái) vào giỏ hàng!`);
  } catch (err) {
    console.error("❌ Lỗi khi thêm giỏ hàng:", err);
    alert("Không thể thêm sản phẩm vào giỏ hàng!");
  }
};



</script>

<style scoped>
.scent-view {
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
  color: #000000;
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
  background: #000000;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}
</style>
