<template>
  <div class="scent-view">
    <Header />

    <div class="scent-page">
      <h2 class="title">Tất cả mùi hương</h2>

      <div class="scent-grid">
        <div
          v-for="scent in scents"
          :key="scent.id"
          class="scent-card"
          @click="goToScent(scent.id)"
        >
          <!-- <img
            :src="getImageUrl(scent.image)"
            :alt="scent.name"
            class="scent-img"
            @error="onImageError"
          /> -->
          <h3>{{ scent.name }}</h3>
          <p class="scent-desc">{{ scent.description || "Không có mô tả" }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import Header from "@/components/Header.vue";

export default {
  name: "AllScent",
  components: {
    Header,
  },
  data() {
    return {
      scents: [],
      defaultImage: "/images/no-image.png",
    };
  },
  mounted() {
    this.fetchScents();
  },
  methods: {
    async fetchScents() {
      try {
        const res = await axios.get("http://127.0.0.1:8000/api/indexS");
        this.scents = res.data;
      } catch (err) {
        console.error("Không lấy được danh sách mùi hương:", err);
      }
    },
    // getImageUrl(image) {
    //   if (!image) return this.defaultImage;
    //   if (image.startsWith("http")) return image;
    //   return `http://127.0.0.1:8000/storage/${image}`;
    // },
    // onImageError(e) {
    //   e.target.src = this.defaultImage;
    // },
    goToScent(id) {
      this.$router.push(`/scent/${id}`);
    },
  },
};
</script>

<style scoped>
.scent-view {
  font-family: "Inter", sans-serif;
  background-color: #fafafa;
  min-height: 100vh;
}

.scent-page {
  padding: 40px;
  background: #fafafa;
}

.title {
  text-align: center;
  margin-bottom: 30px;
  font-size: 28px;
  font-weight: bold;
}

.scent-grid {
  display: flex;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 20px;
}

.scent-card {
  background: rgb(220, 228, 233);
  border-radius: 12px;
  text-align: center;
  padding: 15px;
  transition: 0.2s;
  cursor: pointer;
  height: 150px;
  width: 550px;
}

.scent-card:hover {
  transform: scale(1.03);
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* .scent-img {
  width: 100%;
  height: 150px;
  object-fit: cover;
  border-radius: 8px;
} */

.scent-desc {
  font-size: 14px;
  color: #434242;
  margin-top: 5px;
}
</style>
