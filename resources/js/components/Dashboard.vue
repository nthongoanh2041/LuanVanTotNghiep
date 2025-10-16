<template>
  <div class="dashboard-view">
    <Header />

    <!-- Banner -->
    <div class="relative w-full h-screen overflow-hidden">
      <img
        src="/image/banner1.png"
        alt="Banner"
        class="absolute inset-0 w-full h-full object-cover"
      />
    </div>



    <!-- Tìm kiếm và Lọc sản phẩm -->
    <div class="search-filter">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <input
              type="text"
              v-model="searchQuery"
              @input="fetchFeaturedProducts"
              placeholder="Tìm kiếm sản phẩm..."
              class="form-control"
            />
          </div>
          <div class="col-md-6">
            <select v-model="selectedCategory" @change="fetchFeaturedProducts" class="form-control">
              <option value="">Loại sản phẩm</option>
              <option
                v-for="category in categories"
                :key="category.id"
                :value="category.id"
              >
                {{ category.name }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </div>

    <!-- Sản phẩm nổi bật -->
    <ProductsList
  title="Sản phẩm nổi bật"
  :products="featuredProducts"
  @add-to-cart="addToCart"
/>

    <!-- Footer -->
    <Footer />
  </div>
</template>

<script>
import axios from "axios";
import Header from "@/components/Header.vue";
import Footer from "@/components/Footer.vue";
import ProductsList from '@/components/products/ProductsList.vue';

export default {
  name: "DashboardView",
  components: { Header, Footer,ProductsList },
  data() {
    return {
      user: null,
      searchQuery: "",
      selectedCategory: "",
      highlightedItems: [],
      featuredProducts: [],
      categories: [],
    };
  },
  computed: {
    filteredProducts() {
      return this.featuredProducts.filter((product) => {
        const matchesSearch = product.name
          .toLowerCase()
          .includes(this.searchQuery.toLowerCase());
        const matchesCategory = this.selectedCategory
          ? product.category_id === this.selectedCategory
          : true;
        return matchesSearch && matchesCategory;
      });
    },
  },
  mounted() {
    this.loadUser();
    this.fetchCategories();
    this.fetchFeaturedProducts();
    this.fetchHighlightedItems();
  },
  methods: {
    loadUser() {
      const token = localStorage.getItem("token");
      const userData = localStorage.getItem("user");
      if (token && userData) {
        this.user = JSON.parse(userData);
      } else {
        this.$router.push("/login");
      }
    },
    fetchCategories() {
      axios
        .get("http://localhost:8000/api/categories")
        .then((response) => {
          this.categories = response.data;
        })
        .catch((error) => {
          console.error("Error fetching categories:", error);
        });
    },
     async fetchHighlightedItems() {
  try {
    const res = await axios.get("http://localhost:8000/api/index");
    const data = res.data;

    if (Array.isArray(data)) {
      this.highlightedItems = data.map(category => ({
        title: category.name,
        description: category.description || 'Không có mô tả',
        link: `/category/${category.id}`,
      }));
    } else {
      console.error("Dữ liệu category không hợp lệ:", data);
    }
  } catch (error) {
    console.error("Lỗi khi tải categories:", error);
  }
},
    fetchFeaturedProducts() {
      const params = {
        search: this.searchQuery,
        category: this.selectedCategory,
      };
      axios.get('http://localhost:8000/api/indexP', { params })
        .then(response => {console.log(response.data);
if (Array.isArray(response.data)) {
 this.featuredProducts = response.data.map(product => {
  let imagePath = product.image ? product.image.replace(/[\r\n\s]+/g, '').trim() : null;
  if (imagePath && !imagePath.startsWith('http')) {
    imagePath = `http://localhost:8000${imagePath.startsWith('/') ? '' : '/'}${imagePath}`;
  }
  return { ...product, image: imagePath || '/no-image.png' };
});
        } else {
  console.error('Invalid data from API:', response.data);
}
        });

    },
    addToCart(product) {
      // Logic thêm sản phẩm vào giỏ hàng
    },
    formatCurrency(value) {
      return new Intl.NumberFormat("vi-VN", {
        style: "currency",
        currency: "VND",
      }).format(value);
    },
  },
};
</script>

<style scoped>
.dashboard-view {
  font-family: "Inter", sans-serif;
  background-color: #fafafa;
}

/* Thanh chào user */
.welcome-bar {
  background: linear-gradient(to right, #f9fafb, #f3f4f6);
  padding: 40px 0;
  border-bottom: 1px solid #e5e7eb;
}

.welcome-bar h2 {
  font-size: 1.8rem;
  font-weight: 700;
  color: #111827;
}

.welcome-bar p {
  color: #6b7280;
  font-size: 1rem;
}

.username {
  color: #000;
  font-weight: 800;
}

/* Dưới đây giữ nguyên style từ HomeView */
.highlighted-info {
  background: linear-gradient(to right, #f8f9fa, #e9ecef);
  padding: 40px 0;
}

.highlighted-item {
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 20px;
  text-align: center;
  transition: transform 0.3s ease;
}

.highlighted-item:hover {
  transform: translateY(-10px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

.search-filter {
  padding: 20px 0;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

</style>
