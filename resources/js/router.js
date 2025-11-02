import { createRouter, createWebHistory } from 'vue-router'
import HomePage from './components/HomePage.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import Dashboard from './components/Dashboard.vue'
import ProductsList from '@/components/products/ProductsList.vue';
import DashboardAdmin from './components/admin/DashboardAdmin.vue'
import ProductManager from './components/admin/ProductManager.vue'

import ProductForm from './components/admin/ProductForm.vue';
import ProductEdit from './components/admin/ProductEdit.vue'
import CategorieManager from './components/admin/CategorieManager.vue'
import CategorieForm from './components/admin/CategorieForm.vue';
import CategorieEdit from './components/admin/CategorieEdit.vue'
import ProductDetail from '@/components/products/ProductDetail.vue';
import CategorieProduct from '@/components/admin/CategorieProduct.vue';
import AllCategory from '@/components/products/AllCategory.vue';
import Category_Product from '@/components/products/Category_Product.vue';
import AllScent from '@/components/products/AllScent.vue';
import Scent_Product from '@/components/products/Scent_Product.vue';
import Cart from '@/components/Cart.vue';
import Order from '@/components/Order.vue';
import OrderManager from'@/components/admin/OrderManager.vue';
import OrderItemsManager from'@/components/admin/OrderItemsManager.vue';



const routes = [
  { path: '/', component: HomePage },        // ✅ Trang mặc định
  { path: '/register', component: Register }, // ✅ Trang đăng ký
  { path: '/login', component: Login }, // ✅ Trang đăng ký
  { path: '/dashboard', component: Dashboard },



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
    path: '/admin/categorie/:id/products',
    name: 'CategorieProduct',
    component: CategorieProduct,
     meta: { requiresAuth: true, adminOnly: true },
  },
{
     path: '/admin/categorie/:id/edit',
    name: 'CategorieEdit',
    component: CategorieEdit,
    meta: { requiresAuth: true, adminOnly: true },
},
///////order admin
{
     path: '/admin/order-list',
    name: 'OrderManager',
    component: OrderManager,
    meta: { requiresAuth: true, adminOnly: true },
},
{
    path: '/admin/orders/:id',
    name: 'OrderItemsManager',
    component: OrderItemsManager,
    meta: { requiresAuth: true, adminOnly: true },
},


/////////
{
     path: '/products',
    name: 'AllCategory',
    component: AllCategory,
    props: true,
},
{
     path: '/category/:id',
    name: 'Category_Product',
    component: Category_Product,
    props: true,
},
/////
{
     path: '/product',
    name: 'AllScent',
    component: AllScent,
    props: true,
},
{
     path: '/scent/:id',
    name: 'Scent_Product',
    component: Scent_Product,
    props: true,
},

  {
    path: '/admin/dashboard',
    component: DashboardAdmin,
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
  },
  {
    path: '/admin/product-manager',

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
},

///
{
     path: '/cart',
    name: 'Cart',
    component: Cart,
    props: true,
},
////////////////////////////////
{
     path: '/order',
    name: 'Order',
    component: Order,
    props: true,
},





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
