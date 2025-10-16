<template>
  <div class="product-list">
    <h2 class="section-title">Sản phẩm nổi bật</h2>

    <div v-if="products.length > 0" class="card-container">
      <div
        v-for="product in products"
        :key="product.id"
        class="product-card"
      >
        <router-link :to="'/products/' + product.id" class="card-link">
          <img :src="product.image" class="card-img-top" :alt="product.name">

        </router-link>

        <h5 class="product-name">{{ product.name }}</h5>
        <p class="product-price">{{ formatCurrency(product.price) }}</p>

        <div class="btn-group">
          <router-link
            :to="'/products/' + product.id"
            class="btn btn-outline-secondary btn-sm"
          >
            Chi tiết
          </router-link>
          <button
            @click="$emit('add-to-cart', product)"
            class="btn btn-primary btn-sm"
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
export default {
  name: "ProductList",
  props: {
    products: {
      type: Array,
      default: () => [],
    },
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },
}
};
</script>

<style scoped>
.product-list {
  padding: 20px;
}

.section-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 20px;
}

/* GRID CHUẨN NGANG */
.card-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 24px;
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
  height: 220px; /* hoặc auto-fit chiều cao khung, tuỳ bạn muốn cố định hay linh hoạt */
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
  color: #666;
  margin-bottom: 12px;
}

.btn-group {
  display: flex;
  justify-content: center;
  gap: 10px;
}
</style>
