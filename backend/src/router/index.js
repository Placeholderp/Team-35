// router/index.js - Updated Configuration
import { createRouter, createWebHistory } from "vue-router";
import AppLayout from '../components/AppLayout.vue'
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Products from "../views/Products/Products.vue";
import AllProducts from "../views/Products/AllProducts.vue";
import PublishedProducts from "../views/Products/PublishedProducts.vue";
import ProductDetails from "../views/Products/ProductDetails.vue";
import ProductImport from "../views/Products/ProductImport.vue";
import RevenueDetails from "../views/Reports/RevenueDetails.vue";
import Users from "../views/Users/Users.vue";
import Customers from "../views/Customers/Customers.vue";
import CustomerView from "../views/Customers/CustomerView.vue";
import Orders from "../views/Orders/Orders.vue";
import OrderView from "../views/Orders/OrderView.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import NotFound from "../views/NotFound.vue";
import AdminRegistration from "../views/AdminRegistration.vue";
import ForcePasswordChange from "../views/ForcePasswordChange.vue";
import store from "../store";
import UserProfile from "../views/Users/UserProfile.vue";
import Report from "../views/Reports/Report.vue";
import OrdersReport from "../views/Reports/OrdersReport.vue";
import CustomersReport from "../views/Reports/CustomersReport.vue";

// Inventory component imports
import InventoryDashboard from "../views/Inventory/InventoryDashboard.vue";
import InventoryMovements from "../views/Inventory/InventoryAdjustmentModal.vue";


// Inventory routes definition
const inventoryRoutes = [
  {
    path: 'inventory',
    name: 'app.inventory',
    component: InventoryDashboard,
    meta: {
      requiresAuth: true
    }
  },
  
  {
    path: 'inventory/movements',
    name: 'app.inventory.movements',
    component: InventoryMovements,
    meta: {
      requiresAuth: true
    }
  },
];

const routes = [
  {
    path: '/',
    redirect: '/app'
  },
  {
    path: '/app',
    name: 'app',
    redirect: '/app/dashboard',
    component: AppLayout,
    meta: {
      requiresAuth: true
    },
    children: [
      {
        path: 'dashboard',
        name: 'app.dashboard',
        component: Dashboard
      },
      {
        path: 'products',
        name: 'app.products',
        component: Products
      },
      {
        path: 'products/all',
        name: 'app.products.all',
        component: AllProducts
      },
      {
        path: '/app/categories',
        name: 'app.categories',
        component: () => import('../views/Categories/Categories.vue'),
        meta: {
          requiresAuth: true,
          title: 'Categories Management'
        }
      },
      {
        path: 'inventory/bulk-adjustment',
        name: 'app.inventory.bulk',
        component: () => import('../views/Inventory/BulkStockAdjustment.vue'),
        meta: {
          requiresAuth: true
        }
      },
      
      {
        path: '/product/:slug',
        name: 'product',
        component: () => import('../views/Products/ProductDetails.vue')
      },
      {
        path: 'products/published',
        name: 'app.products.published',
        component: PublishedProducts
      },
      {
        path: 'profile',
        name: 'app.profile',
        component: UserProfile,
        meta: {
          requiresAuth: true
        }
      },
      {
        path: 'products/import',
        name: 'app.products.import',
        component: ProductImport
      },
      {
        path: 'products/create',
        name: 'app.products.create',
        component: ProductDetails
      },
      {
        path: 'products/:id',
        name: 'app.products.view',
        component: ProductDetails,
        props: true  // Pass route params as component props
      },
      {
        path: 'users',
        name: 'app.users',
        component: Users
      },
      {
        path: 'customers',
        name: 'app.customers',
        component: Customers
      },
      {
        path: 'customers/create',
        name: 'app.customers.create',
        component: CustomerView
      },
      {
        path: 'customers/:id',
        name: 'app.customers.view',
        component: CustomerView,
        props: true  // Pass route params as component props
      },
      {
        path: 'orders',
        name: 'app.orders',
        component: Orders
      },
      {
        path: 'orders/:id',
        name: 'app.orders.view',
        component: OrderView,
        props: true  // Pass route params as component props
      },
      {
        path: 'report',
        name: 'reports',
        component: Report,
        redirect: to => {
          // Default to customers report if no specific report is selected
          return { 
            name: 'reports.customers', 
            params: { date: to.params.date || 'all' } 
          };
        },
        children: [
          {
            path: 'customers/:date?',
            name: 'reports.customers',
            component: CustomersReport,
            props: true  // Pass route params as component props
          },
          {
            path: 'orders/:date?',
            name: 'reports.orders',
            component: OrdersReport,
            props: true  // Pass route params as component props
          },
          {
            path: 'revenue',
            name: 'reports.revenue',
            component: RevenueDetails
          }
        ]
      },
      
      // Add inventory routes here
      ...inventoryRoutes,
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/admin/register',
    name: 'admin.register',
    component: AdminRegistration,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/force-password-change',
    name: 'forcePasswordChange',
    component: ForcePasswordChange,
    meta: {
      requiresAuth: true,
      requiresPasswordChange: true
    }
  },
  {
    path: '/inventory/movements',
    name: 'app.inventory.movements',
    component: InventoryMovements,
    meta: {
      requiresAuth: true
    }
  },
  {
    path: '/request-password',
    name: 'requestPassword',
    component: RequestPassword,
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/reset-password/:token',
    name: 'resetPassword',
    component: ResetPassword,
    props: true,  // Pass route params as component props
    meta: {
      requiresGuest: true
    }
  },
  {
    path: '/:pathMatch(.*)*',  // Catch-all route syntax for Vue Router 4
    name: 'notfound',
    component: NotFound,
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

router.beforeEach((to, from, next) => {
  // Check if the user token exists
  const isAuthenticated = !!store.state.user.token;
  const user = store.state.user.data;
  
  // Check if user needs to change password (first login)
  const needsPasswordChange = user && user.force_password_change === true;
  
  if (to.meta.requiresAuth && !isAuthenticated) {
    // Redirect to login if a protected route is accessed without authentication
    return next({ 
      name: 'login',
      query: { redirect: to.fullPath } // Save the path user was trying to access
    });
  } else if (to.meta.requiresGuest && isAuthenticated) {
    // Redirect to dashboard if a guest-only route is accessed while authenticated
    return next({ name: 'app.dashboard' });
  } else if (needsPasswordChange && to.name !== 'forcePasswordChange') {
    // Redirect to password change if the user needs to change their password
    return next({ name: 'forcePasswordChange' });
  } else if (to.meta.requiresPasswordChange && !needsPasswordChange) {
    // Prevent accessing the password change page if not required
    return next({ name: 'app.dashboard' });
  } else {
    // Proceed as normal
    return next();
  }
});

export default router;