import { createRouter, createWebHistory } from 'vue-router'
import HomePage from './components/HomePage.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import Dashboard from './components/Dashboard.vue'
import ProductsList from '@/components/products/ProductsList.vue';
import ProductByCategory from '@/components/products/ProductByCategory.vue';
import ProductByScent from '@/components/products/ProductByScent.vue';
import DashboardAdmin from './components/admin/DashboardAdmin.vue'
import ProductManager from './components/admin/ProductManager.vue'

import ProductForm from './components/admin/ProductForm.vue';
import ProductEdit from './components/admin/ProductEdit.vue'
import CategorieManager from './components/admin/CategorieManager.vue'
import CategorieForm from './components/admin/CategorieForm.vue';
import CategorieEdit from './components/admin/CategorieEdit.vue'
import ProductDetail from '@/components/products/ProductDetail.vue';


const routes = [
  { path: '/', component: HomePage },        // ✅ Trang mặc định
  { path: '/register', component: Register }, // ✅ Trang đăng ký
  { path: '/login', component: Login }, // ✅ Trang đăng ký
  { path: '/dashboard', component: Dashboard },
  {
    path: '/category/products/:id',
    name: 'ProductByCategory',
    component: ProductByCategory,
    props: true,
  },
  {
  path: '/products/scent/:id',
  name: 'ProductByScent',
  component: ProductByScent,
  props: true,
},


{
  path: "/admin/product-form",
  name: 'ProductForm',
  component: ProductForm,
  props: true,
},
{
  path: "/admin/categorie-list",
  name: 'CategorieManager',
  component: CategorieManager,
  props: true,
},
{
  path: "/admin/categorie-form",
  name: 'CategorieForm',
  component: CategorieForm,
  props: true,
},
{
    path: '/admin/categorie/:id/edit',
    name: 'CategorieEdit',
    component: CategorieEdit,
    props: true,
  },


  {
    path: '/admin/dashboard',
    component: DashboardAdmin,
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
  },
  {
    path: '/admin/product-manager',
// <<<<<<< HEAD
//     component: ProductManager,
//     meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
//   },
// =======
    component: ProductManager, // tên file
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
  },
  {
  path: '/admin/products/:id/edit',
 component: ProductEdit,
  meta: { requiresAuth: true, adminOnly: true },
},
  {
  path: '/products/:id/show',
  component: ProductDetail,
}





]


const router = createRouter({
  history: createWebHistory(),
  routes,
})

// 🧩 Route Guard (bảo vệ route)
router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem('user'));
  const token = localStorage.getItem('token');

  // Nếu route yêu cầu đăng nhập mà chưa có token → quay về login
  if (to.meta.requiresAuth && !token) {
    next('/login');
    return;
  }

  // Nếu route chỉ dành cho admin mà role không phải admin → về homepage
  if (to.meta.adminOnly && user?.role?.trim().toLowerCase() !== 'admin') {
    next('/');
    return;
  }

  next(); // cho phép truy cập
});
router.beforeEach((to, from, next) => {
  const isAdminRoute = to.path.startsWith('/admin'); // Kiểm tra xem route có phải là các route admin không

  if (isAdminRoute) {
    // Nếu là route admin, sử dụng HeaderAdmin
    document.title = 'Admin Page';
    // Bạn có thể set document.title cho các trang admin
    next();
  } else {
    // Nếu không phải route admin, sử dụng Header chung
    document.title = 'User Page';
    next();
  }
});

export default router
