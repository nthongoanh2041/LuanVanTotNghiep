<template>
  <div class="home-view">
    <Header />
    <!-- Banner -->
    <div class="relative w-full h-screen overflow-hidden">

  <img
    src="/image/banner1.png"
    alt="Banner"
    class="absolute inset-0 w-full h-full object-cover"
  />
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
import axios from 'axios';
import Header from '@/components/Header.vue'
import Footer from '@/components/Footer.vue'
import ProductsList from '@/components/products/ProductsList.vue';

export default {
  name: 'HomeView',
  components: { Header, Footer, ProductsList },
  // phần data, computed, methods giữ nguyên
  data() {
    return {
      searchQuery: '',
      selectedCategory: '',
    highlightedItems: [],
      featuredProducts: [],
      categories: [],
    };
  },
  computed: {
    filteredProducts() {
      return this.featuredProducts.filter(product => {
        const matchesSearch = product.name.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesCategory = this.selectedCategory ? product.category_id === this.selectedCategory : true;
        return matchesSearch && matchesCategory;
      });
    },
  },
  mounted() {
    this.fetchFeaturedProducts();
     this.fetchHighlightedItems();
  },
  methods: {
    async fetchHighlightedItems() {
  try {
    const res = await axios.get("http://localhost:8000/api/index");
    const data = res.data;

    if (Array.isArray(data)) {
      this.highlightedItems = data.map(category => ({
        title: category.name,
        description: category.description || "Không có mô tả",
        link: `/show/${category.id}`, // link động tới từng loại sản phẩm
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
      // Handle adding product to cart
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(value);
    },
  },
};
</script>
<style scoped>
/* CSS for HomeView and other sections */
.home-view {
  font-family: 'Inter', sans-serif;
  background-color: #fafafa;
}
.highlighted-item:hover {
  transform: translateY(-6px);
}

.banner img {
  width: 100%;
  height: 100%;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

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

.highlighted-item h3 {
  font-size: 24px;
  color: #343a40;
}

.search-filter {
  padding: 20px 0;
  background-color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  margin-bottom: 40px;
}

.search-filter .form-control {
  margin: 10px 0;
}



.btn {
  padding: 6px 12px;
  font-size: 14px;
  text-align: center;
  white-space: nowrap;
  vertical-align: middle;
  cursor: pointer;
  border: 1px solid transparent;
  border-radius: 4px;
  transition: all 0.3s ease;
}

.btn-outline-secondary {
  color: #6c757d;
  background-color: transparent;
  border-color: #6c757d;
}

.btn-outline-secondary:hover {
  color: #fff;
  background-color: #6c757d;
  border-color: #6c757d;
}

.btn-primary {
  color: #fff;
  background-color: #007bff;
  border-color: #007bff;
}

.btn-primary:hover {
  background-color: #0056b3;
  border-color: #0056b3;
}
</style>
