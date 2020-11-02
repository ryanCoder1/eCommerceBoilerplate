
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import VueRouter from 'vue-router';
import { routes } from './routes';
import StoreData from './store';
import Vuex from 'vuex';
import axios from 'axios';
import VueAxios from 'vue-axios';

import visibility from 'vue-visibility-change';

import { library } from '@fortawesome/fontawesome-svg-core';
import { faSpinner } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fab  } from '@fortawesome/free-brands-svg-icons'
import { fas } from '@fortawesome/free-solid-svg-icons';
library.add(fas, fab)
library.add(faSpinner)
Vue.component('font-awesome-icon', FontAwesomeIcon)

// registry directive
Vue.use(visibility);




let Navheader = require('./components/nav/nav-header.vue').default;
let Footerfull = require('./components/footer/footer.vue').default;
let Loader = require('./components/pages/loader.vue').default;


Vue.use(VueAxios, axios);
Vue.use(VueRouter);


const router = new VueRouter({

    mode: "history",
    routes,

});
const store = new Vuex.Store(StoreData);

router.beforeEach((to, from, next) =>{
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const requiresAdminAuth = to.matched.some(record => record.meta.requiresAdminAuth);
  const requiresContent = to.matched.some(record => record.meta.requiresContent);
  const currentUser = store.state.userInfo.currentUser;
  const currentAdmin = store.state.admin.currentAdmin;
  const isLoggedInDashboard = store.state.admin.isLoggedInDashboard;
  const user_id = store.state.user_id;


 if(to.path == '/admin/login'  && isLoggedInDashboard){
    next('/dashboard');
  }
  else{
    next();
  }
  if(to.path == '/home' || to.path.indexOf('/category/') != -1){
      store.dispatch('admin/logoutQuiet');
    setTimeout(() => {
      router.app.activeLoader = false;
      router.app.load = false;
    },10)
    setTimeout(() => {
      router.app.activeLoader = true;
    },2250)
    setTimeout(() => {
      router.app.load = true;
    },3000)
     next();
   }

});




  //// If you intend to programmatically custom event listener code
  //// (e.g. `this.$refs.gmap.$on('zoom_changed', someFunc)`)
  //// instead of going through Vue templates (e.g. `<GmapMap @zoom_changed="someFunc">`)
  //// you might need to turn this on.
  // autobindAllEvents: false,

  //// If you want to manually install components, e.g.
  //// import {GmapMarker} from 'vue2-google-maps/src/components/marker'
  //// Vue.component('GmapMarker', GmapMarker)
  //// then disable the following:
  // installComponents: true,

const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
    'nav-header':  Navheader,
    'footer-full':  Footerfull,
    'loader': Loader,
    },
    data: {
      load: true,
      activeLoader: false,
    },
mounted() {


 },
 watch: {

  '$route' (to, from) {
      // start each page component at top of screen unless an anchor is preset
        // document.getElementById('app').scrollIntoView();

    }

},
template: `<div>
           <nav-header></nav-header>
           <router-view ></router-view>
           <footer-full
           v-if="load"></footer-full>
           <loader
            v-if="!load"
            v-bind:active-loader="activeLoader"></loader>
           </div>`

});
