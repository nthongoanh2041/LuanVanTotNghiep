import { createRouter, createWebHistory } from 'vue-router'
import HomePage from './components/HomePage.vue'
import Register from './components/Register.vue'
import Login from './components/Login.vue'
import Dashboard from './components/Dashboard.vue'
import ProductsList from '@/components/products/ProductsList.vue';
import DashboardAdmin from './components/admin/DashboardAdmin.vue'
import ProductManager from './components/admin/ProductManager.vue'
import ProductStock from './components/admin/ProductStock.vue'
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
import ManufacturerManager from './components/admin/ManufacturerManager.vue'
import ManufacturerForm from './components/admin/ManufacturerForm.vue'
import ManufacturerEdit from './components/admin/ManufacturerEdit.vue'
import ScentManager from './components/admin/ScentManager.vue'
import ScentForm from './components/admin/ScentForm.vue'
import ScentEdit from './components/admin/ScentEdit.vue'
import StockRequest from './components/admin/StockRequest.vue'
import StockImport from './components/admin/StockImport.vue'
import StockImportHistory from './components/admin/StockImportHistory.vue'
import ImportDetail from './components/admin/ImportDetail.vue'

const routes = [
  { path: '/', component: HomePage },        // ✅ Trang mặc định
  { path: '/register', component: Register }, // ✅ Trang đăng ký
  { path: '/login', component: Login }, // ✅ Trang đăng ký
  { path: '/dashboard', component: Dashboard, },

  /////////////////// [ADMIN] ////////////////////////
  {
    path: '/admin/dashboard',
    component: DashboardAdmin,
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
  },
/////Product
{
    path: '/admin/product-manager',
    component: ProductManager, // tên file
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
},

{
  path: "/admin/product-form",
  name: 'ProductForm',
  component: ProductForm, // tên file
  meta: { requiresAuth: true, adminOnly: true },
},

{
  path: '/admin/products/:id/edit',
  component: ProductEdit,
  meta: { requiresAuth: true, adminOnly: true },
},
{
  path: '/admin/stock-manager',
  component: ProductStock,
  meta: { requiresAuth: true, adminOnly: true },
},

{
  path: "/admin/stock-request/:id",
  component: StockRequest,
  meta: { requiresAuth: true, adminOnly: true },
},

{
  path: "/admin/stock-import/:id",
  component: StockImport,
  meta: { requiresAuth: true, adminOnly: true },
},
{
  path: "/admin/stock-history",
  component: StockImportHistory,
  meta: { requiresAuth: true, adminOnly: true },
},
{
  path: "/admin/import/:id",
  component: ImportDetail,
  meta: { requiresAuth: true, adminOnly: true },
},


////Categories
{
  path: "/admin/categorie-manager",
  name: 'CategorieManager',
  component: CategorieManager,
  meta: { requiresAuth: true, adminOnly: true },
},

{
  path: "/admin/categorie-form",
  name: 'CategorieForm',
  component: CategorieForm,
  meta: { requiresAuth: true, adminOnly: true },
},

{
     path: '/admin/categorie/:id/edit',
    name: 'CategorieEdit',
    component: CategorieEdit,
    meta: { requiresAuth: true, adminOnly: true },
},

{
    path: '/admin/categorie/:id/products',
    name: 'CategorieProduct',
    component: CategorieProduct,
    meta: { requiresAuth: true, adminOnly: true },
  },

///////Manufacturer
{
 path: '/admin/manufacturer-manager',
 component: ManufacturerManager, // tên file
 meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
  },

{
  path: "/admin/manufacturer-form",
  name: 'ManufacturerForm',
  component: ManufacturerForm,
  meta: { requiresAuth: true, adminOnly: true },
},

{
  path: "/admin/manufacturer-edit/:id",
  name: 'ManufacturerEdit',
  component: ManufacturerEdit,
  meta: { requiresAuth: true, adminOnly: true },
},

//////Scent
{
    path: '/admin/scent-manager',
    component: ScentManager, // tên file
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
},
{
    path: '/admin/scent-form',
    component: ScentForm, // tên file
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
},
{
    path: '/admin/scent-edit/:id',
    component: ScentEdit, // tên file
    meta: { requiresAuth: true, adminOnly: true }, // ✅ Chỉ admin mới vào được
},

///////order
{
     path: '/admin/order-manager',
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


/////////////////// [USER] ////////////////////////
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
  path: '/products/:id/show',
  component: ProductDetail,
},

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

router.beforeEach((to, from, next) => {
  const isAdminRoute = to.path.startsWith('/admin');

  const adminToken = sessionStorage.getItem('admin_token');
  const userToken = localStorage.getItem('user_token');

  // Nếu route yêu cầu đăng nhập
  if (to.meta.requiresAuth) {
    if (isAdminRoute && !adminToken) return next('/login');
    if (!isAdminRoute && !userToken) return next('/login');
  }

  next();
});

export default router
