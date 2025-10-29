<template>
    <div class="category-view">
    <Header />
        <div class="category-page">
        <h2 class="title">Các loại sản phẩm</h2>
        <div class="category-grid">
      <div
        v-for="cat in categories"
        :key="cat.id"
        class="category-card"
        @click="goToCategory(cat.id)"
      >
        <img
          :src="getImageUrl(cat.image)"
          :alt="cat.name"
          class="category-img"
          @error="onImageError"
        />
        <h3>{{ cat.name }}</h3>
      </div>
    </div>
    </div>
  </div>


</template>

<script>
import axios from "axios";
import Header from "@/components/Header.vue";

export default {
  name: "CategoryList",
   components: {
    Header, //  phải đăng ký ở đây
  },
  data() {
    return {
      categories: [],
      defaultImage: "/images/no-image.png", // fallback khi lỗi ảnh
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    async fetchCategories() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/index");
        this.categories = res.data;
      } catch (err) {
        console.error("Không lấy được danh mục:", err);
      }
    },
    getImageUrl(image) {
      if (!image) return this.defaultImage;
      if (image.startsWith("http")) return image;
      // ✅ chỉnh đúng đường dẫn ảnh từ Laravel
      return `http://127.0.0.1:8000/storage/${image}`;
    },
    goToCategory(id) {
      this.$router.push(`/category/${id}`);
    },
    onImageError(e) {
      e.target.src = this.defaultImage;
    },
  },
};
</script>

<style scoped>
.category-view {
  font-family: "Inter", sans-serif;
  background-color: #fafafa;
  min-height: 100vh;
}
.category-page {
  padding: 40px;
  background: #fafafa;
}
.title {
  text-align: center;
  margin-bottom: 30px;
  font-size: 28px;
  font-weight: bold;
}
.category-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;

}
.category-card {
  background: white;
  border-radius: 12px;
  text-align: center;
  padding: 15px;
  transition: 0.2s;
  cursor: pointer;

}
.category-card:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
.category-img {
  width: 100%;
  height: 250px;
  object-fit: cover;
  border-radius: 8px;
}
</style>
