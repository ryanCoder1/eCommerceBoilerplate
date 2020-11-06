
import About from './components/pages/about.vue';
import Login from './components/auth/login.vue';
import Register from './components/auth/register.vue';
import Logout from './components/auth/logout.vue';
import SendEmail from './components/auth/sendemail.vue';
import ResetPassword from './components/auth/resetPassword.vue';
import Dashboard from './components/adminDashboard/dashboard.vue';
import DashboardBanner from './components/adminDashboard/banner.vue';
import DashboardAbout from './components/adminDashboard/about.vue';
import DashboardProductsCreate from './components/adminDashboard/productscreate.vue';
import DashboardProductsEdit from './components/adminDashboard/productsedit.vue';
import DashboardProductsShowHomePage from './components/adminDashboard/productsshowhomepage.vue';
import DashboardProductDefaultImage from './components/adminDashboard/productdefaultimage.vue';
import DashboardProductCategoryCreate from './components/adminDashboard/productcategorycreate.vue';
import DashboardProductGroups from './components/adminDashboard/productgroups.vue';
import DashboardProductImages from './components/adminDashboard/productimages.vue';
import DashboardProductCategoryEdit from './components/adminDashboard/productcategoryedit.vue';
import DashboardCategories from './components/adminDashboard/categories.vue';
import DashboardSlideFeatured from './components/adminDashboard/slidefeatured.vue';
import DashboardSlideSpecials from './components/adminDashboard/slidespecials.vue';
import DashboardSlideBestSellers from './components/adminDashboard/slidebestsellers.vue';
import DashboardViewCustomers from './components/adminDashboard/viewcustomers.vue';
import DashboardViewOrders from './components/adminDashboard/vieworders.vue';
import DashboardViewPayments from './components/adminDashboard/viewpayments.vue';
import DashboardDashboardUsers from './components/adminDashboard/dashboardusers.vue';
import DashboardDashboardLogout from './components/adminDashboard/dashboardlogout.vue';
import DashboardOrderDimensions from './components/adminDashboard/orderdimensions.vue';
import DashboardPhoneNumberInfo from './components/adminDashboard/phonenumberinfo.vue';
import DashboardEmailAddressInfo from './components/adminDashboard/emailaddressinfo.vue';
import DashboardSocialMediaInfo from './components/adminDashboard/socialmediainfo.vue';
import AdminLogin from './components/adminAuth/login.vue';
import AdminLogout from './components/adminAuth/logout.vue';
import AdminCreateAccount from './components/adminAuth/admincreateaccount.vue';
import Admin from './components/adminAuth/admin.vue';
import Home from './components/clientside/home.vue';
import CateogryProducts from './components/clientside/category.vue';
import Product from './components/clientside/product/product.vue';
import DashboardLayout from './components/adminDashboard/dashboardlayout.vue';
import PageNotFound from './components/pages/pagenotfound.vue';
import FeatureNeedsService from './components/pages/featureneedsservice.vue';
import FeatureNeedsServiceDashboard from './components/pages/featureneedsservicedashboard.vue';
import Privacy from './components/pages/privacy.vue';
import AdminInvite from './components/adminAuth/adminInvite.vue';


export const routes = [

  { path: '/home',
    component: Home,
    name: 'Home',
    meta: {
        requiresAuth: false
    }
 },
  { path: '/',
  component: Home,
   name: 'Index',
   meta: {
       requiresAuth: false
   }
 },
 { path: '/category/:category',
   component: CateogryProducts,
   name: 'CategoryProducts',
   meta: {
       requiresAuth: false
   }
},
{ path: '/product/:product',
  component: Product,
  name: 'Product',
  meta: {
      requiresAuth: false
  }
},
  { path: '/login',
   component: Login,
    name: 'Login',
    meta: {
        requiresAuth: false
    }
   },
  { path: '/register',
  component: Register,
  name: 'Register',
  meta: {
      requiresAuth: false
  }

},
  { path: '/logout',
   component: Logout,
    name: 'Logout',
    meta: {
        requiresAuth: true
    }
  },
  { path: '/verify/:email/:token',
  component: Login,
   name: 'VerifyEmail',
   meta: {
       requiresAuth: false
   }
},
{ path: '/sendemail',
 component: SendEmail,
  name: 'SendEmail',
  meta: {
      requiresAuth: false
  }
 },
 { path: '/resetpassword/:email/:token',
 component: ResetPassword,
  name: 'ResetPassword',
  meta: {
      requiresAuth: false
  }
},
{ path: 'admin',
component: Admin,
 name: 'Admin',
 meta: {
      requiresAdminAuth: true
 },
 children: [
   { path: '/admin/adminInvite',
   component: AdminInvite,
    name: 'AdminInvite',
    meta: {
         requiresAdminAuth: true
    }
   },

    { path: '/admin/admincreateaccount/:privilege/:adminVerifyToken',
    component: AdminCreateAccount,
     name: 'AdminCreateAccount',
     meta: {
          requiresAdminAuth: false
     }
   },
   { path: '/admin/verify/:email/:verifyToken',
   component: AdminLogin,
    name: 'AdminVerify',
    meta: {
         requiresAdminAuth: false
    }
  },
  { path: '/admin/login',
  component: AdminLogin,
   name: 'AdminLogin',
   meta: {
        requiresAdminAuth: false
   }
 },
 { path: '/admin/logout',
 component: AdminLogout,
  name: 'AdminLogout',
  meta: {
       requiresAdminAuth: false
  }
},

 ],
},

  { path: '/dashboard',
  component: Dashboard,
   name: 'Dashboard',
   meta: {
        requiresAdminAuth: true
   },
   children: [
     { path: '/dashboard/phonenumberinfo',
     component: DashboardPhoneNumberInfo,
      name: 'DashboardPhoneNumberInfo',
      meta: {
           requiresAdminAuth: true
      }
    },
    { path: '/dashboard/orderdimensions',
    component: DashboardOrderDimensions,
     name: 'DashboardOrderDimensions',
     meta: {
          requiresAdminAuth: true
     }
   },
     { path: '/dashboard/banner',
     component: DashboardBanner,
      name: 'DashboardBanner',
      meta: {
           requiresAdminAuth: true
      }
    },
    { path: '/dashboard/about',
    component: DashboardAbout,
     name: 'DashboardAbout',
     meta: {
          requiresAdminAuth: true
     }
   },
    { path: '/dashboard/emailaddressinfo',
     component: DashboardEmailAddressInfo,
      name: 'DashboardEmailAddressInfo',
      meta: {
           requiresAdminAuth: true
      }
    }, { path: '/dashboard/socialmediainfo',
     component: DashboardSocialMediaInfo,
      name: 'DashboardSocialMediaInfo',
      meta: {
           requiresAdminAuth: true
      }
    },
   { path: '/dashboard/productscreate',
   component: DashboardProductsCreate,
    name: 'DashboardProductsCreate',
    meta: {
         requiresAdminAuth: true
    }
  },
  { path: '/dashboard/productgroups',
  component: DashboardProductGroups,
   name: 'DashboardProductGroups',
   meta: {
        requiresAdminAuth: true
   }
 },
 { path: '/dashboard/productimages',
 component: DashboardProductImages,
  name: 'DashboardProductImages',
  meta: {
       requiresAdminAuth: true
  }
},
  { path: '/dashboard/productsedit',
  component: DashboardProductsEdit,
   name: 'DashboardProductsEdit',
   meta: {
        requiresAdminAuth: true
   }
 },
 { path: '/dashboard/productsshowhomepage',
 component: DashboardProductsShowHomePage,
  name: 'DashboardProductsShowHomePage',
  meta: {
       requiresAdminAuth: true
  }
},
 { path: '/dashboard/productdefaultimage',
 component: DashboardProductDefaultImage,
  name: 'DashboardProductDefaultImage',
  meta: {
       requiresAdminAuth: true
  }
},
{ path: '/dashboard/productcategorycreate',
component: DashboardProductCategoryCreate,
 name: 'DashboardProductCategoryCreate',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/productcategoryedit',
component: DashboardProductCategoryEdit,
 name: 'DashboardProductCategoryEdit',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/categories',
component: DashboardCategories,
 name: 'DashboardCategories',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/slidefeatured',
component: DashboardSlideFeatured,
 name: 'DashboardSlideFeatured',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/slidespecials',
component: DashboardSlideSpecials,
 name: 'DashboardSlideSpecials',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/slidebestsellers',
component: DashboardSlideBestSellers,
 name: 'DashboardSlideBestSellers',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/viewcustomers',
component: DashboardViewCustomers,
 name: 'DashboardViewCustomers',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/vieworders',
component: DashboardViewOrders,
 name: 'DashboardViewOrders',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/viewpayments',
component: DashboardViewPayments,
 name: 'DashboardViewPayments',
 meta: {
      requiresAdminAuth: true
 }
},
{ path: '/dashboard/dashboardusers',
component: DashboardDashboardUsers,
 name: 'DashboardDashboardUsers',
 meta: {
      requiresAdminAuth: true
   }
  },
  { path: '/dashboard/dashboardlogout',
  component: DashboardDashboardLogout,
   name: 'DashboardDashboardLogout',
   meta: {
        requiresAdminAuth: true
     }
    },
],
},
{ path: '/privacy',
    component: Privacy,
    meta: {
        requiresAuth: true,
        requiresContent: true
    }
},
{ path: '/featureneedsservice',
    component: FeatureNeedsService,
    meta: {
        requiresAuth: true,
        requiresContent: true
    }
},
{ path: '/featureneedsservicedashboard',
    component: FeatureNeedsServiceDashboard,
    meta: {
        requiresAuth: true,
    }
},
{ path: '/pagenotfound',
    component: PageNotFound
},
{ path: '*', redirect: '/pagenotfound' }
];
