import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/user/Home.vue";
import { useAdminStore } from '../stores/admin/Admin';
import { useUserStore } from "@/stores/user/User";
import { ElMessage } from "element-plus";
import { useCartStore } from "@/stores/user/Cart";
import { toNumber } from "lodash";

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
      children: [
        {
          path: "",
          name: "main",
          component: () => import("../views/user/Main.vue"),
        },
        {
          path: "cart-detail",
          name: "cart",
          component: () => import("../views/user/CartDetail.vue"),
          beforeEnter: async (to, from, next) => {
            const user = useUserStore();
            const cart = useCartStore();

            if (!user.isAuthenticated()) {
              ElMessage.error('Vui lòng đăng nhập.');
              next({
                path: '/auth/login',
              });
            } else {
              await cart.getCart();
              next();
            }
          },
        },
        {
          path: "product-detail/:slug",
          name: "productDetail",
          component: () => import("../components/ProductDetail.vue"),
          props: route => ({ slug: route.params.slug }),
        },
        {
          path: "shop",
          name: "shop-parent",
          component: () => import("../views/user/Ecommerce.vue"),
          children: [
            {
              path: "",
              name: "shop",
              component: () => import("../views/user/shop/AllProduct.vue"),
            }
          ],
        },
        {
          path: "user",
          name: "user-parent",
          component: () => import("../views/user/User.vue"),
          children: [
            {
              path: "account",
              name: "user-account",
              component: () => import("../views/user/profile/Account.vue"),
              children: [
                {
                  path: "profile",
                  name: "user-profile",
                  component: () => import("../views/user/profile/Profile.vue"),
                },
                {
                  path: "change-email",
                  name: "user-change-email",
                  component: () => import("../views/user/profile/ChangeEmail.vue"),
                },
              ],
            },
            {
              path: "order",
              name: "order",
              component: () => import("../views/user/order/Order.vue"),
              children: [
                {
                  path: "information",
                  name: "order-information",
                  component: () => import("../views/user/order/OrderInformation.vue"),
                },
                {
                  path: "detail/:id",
                  name: "order-detail",
                  component: () => import("../views/user/order/OrderDetail.vue"),
                  props: route => ({ id: route.params.id }),
                }
              ],
            },
          ],
          beforeEnter: (to, from, next) => {
            const user = useUserStore();

            if (!user.isAuthenticated()) {
              ElMessage.error('Vui lòng đăng nhập.');
              next({
                path: '/auth/login',
              });
            } else {
              next();
            }
          },
        },
        {
          path: "checkout",
          name: "checkout",
          component: () => import("../views/user/Checkout.vue"),
          beforeEnter: (to, from, next) => {
            const user = useUserStore();
            const cart = useCartStore();

            if (!user.isAuthenticated()) {
              ElMessage.error('Vui lòng đăng nhập.');
              next({
                path: '/auth/login',
              });
            } else if (!cart.hasCart() || cart.isEmpty()) {
              next({
                path: '/'
              });
            } else next();
          },
        }
      ],
    },
    {
      path: '/auth',
      name: 'auth-user',
      component: () => import("../views/auth/Home.vue"),
      children: [
        {
          path: "login",
          name: "login",
          component: () => import("../views/auth/Login.vue"),
          beforeEnter: (to, from, next) => {
            const user = useUserStore();
    
            if (user.isAuthenticated()) next({ path: '/' });
            else next();
          }
        },
        {
          path: "register",
          name: "register",
          component: () => import("../views/auth/Register.vue"),
          beforeEnter: (to, from, next) => {
            const user = useUserStore();

            if (user.isAuthenticated()) next({ path: '/' });
            else next();
          }
        },
        {
          path: "send-email-verification/:token",
          name: "send-email-verification",
          component: () => import("../views/auth/SendEmailVerification.vue"),
          beforeEnter: (to, from, next) => {
            const user = useUserStore();

            if (user.isAuthenticated()) next({ path: '/' });
            else next();
          }
        },
        {
          path: "verify-email/:id/:hash",
          name: "verify-email",
          component: () => import("../views/auth/VerifyEmail.vue"),
        },
        {
          path: "verify-email-change/:id/:hash",
          name: "verify-email-change",
          component: () => import("../views/auth/VerifyEmailChange.vue"),
        }
      ],
    },
    {
      path: "/admin",
      name: "admin",
      component: () => import("../views/admin/Home.vue"),
      children: [
        {
          path: "dashboard",
          name: "dashboard",
          component: () => import("../views/admin/dashboard/Dashboard.vue"),
          meta: { 
            requiresAuth: true
          },
          children: [
            {
              path: "",
              name: "adminOverview",
              component: () => import("../views/admin/dashboard/Overview.vue"),
            },
            {
              path: "profile",
              name: "adminProfile",
              component: () => import("../views/admin/dashboard/Profile.vue"),
            },
            {
              path: "products",
              name: "adminProductHome",
              component: () => import("../views/admin/dashboard/products/Home.vue"),
              children: [
                {
                  path: "",
                  name: "adminProductList",
                  component: () => import("../views/admin/dashboard/products/Product.vue"),
                },
                {
                  path: ":slug",
                  name: "adminProductDetail",
                  component: () => import("../views/admin/dashboard/products/ProductDetail.vue"),
                  props: route => ({ slug: route.params.slug.toString() }),
                },
                {
                  path: "create-product",
                  name: "adminCreateProduct",
                  component: () => import("../views/admin/dashboard/products/CreateProduct.vue"),
                },
                {
                  path: "edit-product/:slug",
                  name: "adminEditProduct",
                  component: () => import("../views/admin/dashboard/products/EditProduct.vue"),
                  props: route => ({ slug: route.params.slug.toString() }),
                }
              ],
            },
            {
              path: "product-types",
              name: "adminProductType",
              component: () => import("../views/admin/dashboard/products/ProductType.vue"),
            },
            {
              path: "create-product-type",
              name: "adminCreateProductType",
              component: () => import("../views/admin/dashboard/products/CreateProductType.vue"),
            },
            {
              path: "product-colors",
              name: "adminProductColor",
              component: () => import("../views/admin/dashboard/products/ProductColor.vue"),
            },
            {
              path: "create-product-color",
              name: "adminCreateProductColor",
              component: () => import("../views/admin/dashboard/products/CreateProductColor.vue"),
            },
            {
              path: "product-sizes",
              name: "adminProductSize",
              component: () => import("../views/admin/dashboard/products/ProductSize.vue"),
            },
            {
              path: "create-product-size",
              name: "adminCreateProductSize",
              component: () => import("../views/admin/dashboard/products/CreateProductSize.vue"),
            },
            {
              path: "categories",
              name: "adminProductCategory",
              component: () => import("../views/admin/dashboard/categories/Category.vue"),
            },
            {
              path: "create-category",
              name: "adminCreateCategory",
              component: () => import("../views/admin/dashboard/categories/CreateCategory.vue"),
            },
            {
              path: "manufactures",
              name: "adminManufactureHome",
              component: () => import("../views/admin/dashboard/manufactures/Home.vue"),
              children: [
                {
                  path: "",
                  name: "adminManufactureList",
                  component: () => import("../views/admin/dashboard/manufactures/Manufacture.vue"),
                },
                {
                  path: "edit-manufacture/:slug",
                  name: "adminEditManufacture",
                  component: () => import("../views/admin/dashboard/manufactures/EditManufacture.vue"),
                  props: route => ({ slug: route.params.slug.toString() }),
                },
              ],
            },
            {
              path: "create-manufacture",
              name: "adminCreateManufacture",
              component: () => import("../views/admin/dashboard/manufactures/CreateManufacture.vue"),
            },
            {
              path: "banner",
              name: "admin-banner-parent",
              component: () => import("../views/admin/dashboard/banners/Home.vue"),
              children: [
                {
                  path: "",
                  name: "admin-banner-list",
                  component: () => import("../views/admin/dashboard/banners/Banner.vue"),
                },
                {
                  path: "create",
                  name: "admin-banner-create",
                  component: () => import("../views/admin/dashboard/banners/CreateBanner.vue"),
                }
              ],
            },
            {
              path: "account",
              name: "admin-manage-account",
              component: () => import("../views/admin/dashboard/account/Home.vue"),
              children: [
                {
                  path: "",
                  name: "admin-account-list",
                  component: () => import("../views/admin/dashboard/account/Account.vue"),
                },
                {
                  path: "edit/:id",
                  name: "admin-edit-account",
                  component: () => import("../views/admin/dashboard/account/EditAccount.vue"),
                  props: route => ({ id: toNumber(route.params.id) }),
                },
                {
                  path: "register",
                  name: "admin-register",
                  component: () => import("../views/admin/dashboard/account/Register.vue"),
                }
              ],
            },
            {
              path: "customer",
              name: "admin-manage-customer",
              component: () => import("../views/admin/dashboard/customer/Home.vue"),
              children: [
                {
                  path: "",
                  name: "admin-customer-list",
                  component: () => import("../views/admin/dashboard/customer/Customer.vue"),
                }
              ],
            },
            {
              path: "order",
              name: "admin-manage-order",
              component: () => import("../views/admin/dashboard/order/Home.vue"),
              children: [
                {
                  path: "",
                  name: "admin-order-list",
                  component: () => import("../views/admin/dashboard/order/Order.vue"),
                },
                {
                  path: "detail/:id",
                  name: "admin-order-detail",
                  component: () => import("../views/admin/dashboard/order/OrderDetail.vue"),
                  props: route => ({ id: toNumber(route.params.id) }),
                }
              ],
            }
          ],
        },
        {
          path: "login",
          name: "adminLogin",
          component: () => import("../views/admin/Login.vue"),
          meta: { 
            requiresAuth: false,
          },
          beforeEnter: (to, from, next) => {
            const adminStore = useAdminStore();

            if (adminStore.isAuthenticated()) next({ path: '/admin/dashboard' });
            else next();
          }
        },
      ]
    },
    {
      path: "/auth",
      name: "auth",
      component: () => import("../views/auth/Home.vue"),
      children: [
        {
          path: "forgot-password",
          name: "forgotPassword",
          component: () => import("../views/auth/ForgotPassword.vue"),
        }
      ],
    }
  ],
});

router.beforeEach((to, from, next) => {
  const admin = useAdminStore();
  const adminRegex = /^\/admin(\/?.*)$/;
  const adminRoute = adminRegex.test(to.path);

  if (adminRoute && to.name !== 'adminLogin' && !admin.isAuthenticated()) next({ path: '/admin/login' });
  else if (adminRoute && to.name === 'admin') next({ path: '/admin/dashboard' });
  else next();
});

export default router;
