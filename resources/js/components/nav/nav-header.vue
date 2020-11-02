<template>
    <div id="navContainerBody" class="nav-container" v-if="!isLoggedInDashboard">
    <personal-info>
    </personal-info>
      <!-- NAV BODY -->
      <div class="nav-body">

          <div class="left-nav">
              <!-- LOGO SECTION -->

              <!-- HOME & SHOP LIST -->
              <ul class="home-shop-links">
                  <li class="">
                    <router-link :to="{ path: '/home' }"><a>Home</a></router-link>
                  </li>
                  <li class="" v-on:click="showShop($event)">
                    Shop
                    <span  class="nav-shop-caret"  v-if="">
                       <i class="fa fa-caret-down"></i>
                     </span>
                   </li>
               </ul>
           </div>
           <div class="right-nav">
               <!-- SHOPPING CART INFO -->
               <div class="shopping-cart-wrapper">
                 <i class="fa fa-shopping-cart shopping-cart"></i>
                 <span>10</span>
               </div>
               <!-- HAMBURGER MENU FOR SMALL DEVICES -->
               <div class="open-close-menu">
                <span class="nav-menu-text">Menu</span>
                   <span v-if="!openClose" id="open" v-on:click="showMenu()">&#9776;</span>
                   <span v-if="openClose" id="close" v-on:click="showMenu()">&#9776;</span>
               </div>
             </div>
           </div><!-- END OF NAV BODY -->

            <!-- Container that holds featured background for all nav lists -->
              <div id="navContainer" v-if="container">
                <div id="navShop" v-if="shop">Shop</div>

                <div id="navWrapper">
                  <ul class="nav-shop-small" >
                  <li v-on:click="showShop($event)">
                    Shop
                    <span class="nav-shop-caret" v-if="">
                       <i class="fa fa-caret-down"></i>
                     </span>
                   </li>

                 </ul>
                 <div class="category-nav" v-on:mouseleave="targetBgSlideHide()">
                  <ul
                  class="category-nav-ul"

                  v-if="shop">
                    <li
                      class="category-nav-li"
                      v-for="(category, index) in categories"
                      :key="index">
                        <router-link :to="{ name: 'CategoryProducts', params: {category: category.category} }">
                          <a
                          v-on:mouseover="targetBgSlide(index)"
                          v-on:mouseleave="turnOffPulse(index)"
                          v-on:click="saveCategoryIdStore(category.id)">
                              <span class="category-span">
                                {{ category.category }}
                                  <span
                                      v-for="(productCount, indexProd) in productsCount"
                                      :key="indexProd"
                                      v-if="category.id == productCount.category_id">
                                      ( {{ productCount.num }} )
                                    </span>
                                </span>
                              </a>
                            </router-link>

                          </li>

                        </ul>
                          <span class="category-bg-slide"></span>
                        </div>
                    </li>

                  </ul><!-- END OF HOME AND SHOP LISTS -->

                    <!-- site and user account links -->

                        <ul id="menuUl" v-if="menu">
                          <li class="" >
                            <router-link :to="{ path: '/home' }">
                              <a v-on:click="closeNav()">Home</a>
                            </router-link>
                          </li>
                          <li class="" v-if="!isLoggedIn">
                            <router-link :to="{ name: 'Login', path: '/login' }">
                              <a v-on:click="closeNav()">Log in</a>
                            </router-link>
                          </li>
                          <li class="" v-if="!isLoggedIn">
                            <router-link :to="{ name: 'Register', path: '/register' }">
                              <a v-on:click="closeNav()">Register</a>
                            </router-link>
                          </li>
                          <li class="" v-if="isLoggedIn">
                            <router-link :to="{ name: 'Logout', path: '/logout' }">
                              <a v-on:click="closeNav()">Logout</a>
                            </router-link>
                          </li>
                          <li class="">
                            <router-link :to="{ name: 'AdminLogin', path: '/admin/login' }">
                              <a v-on:click="closeNav()">Admin</a>
                            </router-link>
                          </li>
                        </ul><!-- END OF SITE AND USER ACCOUNT LIST -->
                  </div>
              </div><!-- END OF NAV CONTAINER -->
     </div>
</template>



<script>


import {showAt, hideAt} from 'vue-breakpoints'
import { logout } from '../../helper/auth';
import PersonalInfo from '../clientside/personalinfocomponent';

export default {
    components: {
      'personal-info': PersonalInfo,
    },
    data(){
      return{
           categoriesFilter: [],
           categories: [],
           productsCount: [],
           openClose: false,
           shop: false,
           shopMobile: false,
           menu: false,
           container: false,
           categoryMissing: [],
           personalInfo: true,
      }
    },
    created(){
    },
    mounted(){
      // call both methods to show category / products
      this.getCategories();
      this.getProductsCount();



      // set eventListener to watch resize of window width
      window.addEventListener('resize', () => {
         this.windowSize(window.innerWidth);
      }),

      // hide personal info div on scroll down
      window.addEventListener('scroll', () => {
        // only run if isLoggedInDashboard is false/ not logged in to dashboard
          if(!this.isLoggedInDashboard){
            if(window.pageYOffset > 200){
              this.hideNavInfo();
            }else{
              this.showNavInfo();
            }
          }
      })
    },

    watch: {
    '$store.state.refreshNewsFeed': function() {
            if(this.$store.state.refreshNewsFeed){
            this.$store.commit('refreshNewsFeed');
            this.getComments();
          }

        }
    },
    computed: {
        isLoggedIn: function(){
          return this.$store.state.userInfo.isLoggedIn;
        },
         // show Ulists when inside the outfit pages or hide otherwise
         navHeaderViews: function(){
            return this.$store.state.navHeaderView;
          },
          // boolean if false then show Nav bar
          isLoggedInDashboard: function(){
            return this.$store.state.admin.isLoggedInDashboard;
          }
    },
    methods:{
      targetBgSlideHide: function(){
        let elemtSlide = document.getElementsByClassName('category-bg-slide')[0];
         elemtSlide.style.top = '-10px';
         setTimeout(function(){
           elemtSlide.style.opacity = 0;
         },200);

      },
      turnOffPulse: function(index){

         // get span with number of products and pulse it on hover
         let elemtNumber = document.getElementsByClassName('category-span')[index].children[0];
         if(elemtNumber !== undefined){
            elemtNumber.classList.remove('category-number-pulse');
         }

      },
      targetBgSlide: function(index){
        let elemt = document.getElementsByClassName('category-nav-li');
        // get elemt offsetTop, the position in px from top of container
        let oSTop = elemt[index].offsetTop;
        let elemtSlide = document.getElementsByClassName('category-bg-slide')[0];
        // set the opacity and from top to slide over element.
        // there is a transition in css on the category-bg-slide element.
         elemtSlide.style.opacity = 1;
         elemtSlide.style.top = oSTop + 2 + 'px';
         // get span with number of products and pulse it on hover
         let elemtNumber = document.getElementsByClassName('category-span')[index].children[0];
         if(elemtNumber !== undefined){
            elemtNumber.classList.add('category-number-pulse');
         }

      },
      hideNavInfo: function(){
        let personalHeight = document.getElementById('navPersonalInfo').offsetHeight;
        let elemt = document.getElementById('navContainerBody');
        elemt.style.top = '-' + personalHeight + 'px';
      },
      showNavInfo: function(){
        let elemt = document.getElementById('navContainerBody');
        elemt.style.top = "0px";
      },
      saveCategoryIdStore: function(id){
        this.$store.dispatch('products/getCategoryId', id);
        this.shop = !this.shop;
        this.container = !this.container;
        this.menu = false;
      },
      hideCategoryList: function(){
        // hide any class nav-categories that have an zero length children
        // meaning their is an category that doesn't have any products
        setTimeout(function(){
          let categories = document.getElementsByClassName('nav-categories');
          console.log(categories);
          for(let i = 0; i < categories.length; i++){
              if(categories[i].children[0].children.length == 0){
                categories[i].style.display = "none";
                }
            }
          },100);

      },


      removeCategoryWithNoProduct: function(){

        // run through categories array
        for(let i = 0; i < this.categories.length; i++){
          // run through products array
          for(let m = 0; m < this.products.length; m++){
            // find categories that have products and push to array
            if(this.products[m].category_id == this.categories[i].id){
              this.categoriesFilter.push(i);
            }
          }
        }
       // get the unique array values so no duplicates
        let unique = this.categoriesFilter.filter((item, i, ar) => ar.indexOf(item) === i);

        for(let m = 0; m < unique.length; m++){
            // start search of last number above zero and below last value
              if(m > 0 && m < unique.length){
              // chech the current m value agains m - 1 value and see if equal to 1
              if(unique[m] - unique[m-1] != 1) {
                this.categoryMissing.push(m);
              }
            }
        }
        // if categoryMissing is empty, push a null value to run v-for loop in template
        if(this.categoryMissing.length == 0){
          this.categoryMissing.push('null');
        }

      },
      closeNav: function(){
        // close nav menu when router-link clicked
        this.shop = !this.shop;
        this.container = !this.container;
        this.menu = !this.menu;
      },

      mount: function(){
        var self = this;
          // call getComments for comment count every 10 seconds
          setInterval(function(){
              self.getComments();
          }, 1000)
        },
        showShop: function(event){


          // this.hideProductsList();
          // this.hideCategoryList();
          this.shop = !this.shop;
          // if(this.shop === true){
          //   this.rotateCaret(event.target.children[0].children[0], 180);
          // }else{
          //   this.rotateCaret(event.target.children[0].children[0], 0);
          // }

          if(window.innerWidth > 768){
              if(this.menu === true && this.container === true){
                this.menu = false;
                this.container = false;
                this.shop = false;
              }
              this.container = !this.container;
              console.log(this.container);
              if(this.shop === false){
                this.container = false;
              }
              if(this.shop == true && this.container === false){
                this.container = true;
                this.shop = true;
              }

            }
       },
        showMenu: function(){
           this.menu = !this.menu;
           this.container = !this.container;

          console.log(this.menu);
          console.log(this.container);

        if(window.innerWidth > 768){
          if(this.menu === true && this.container === true){
            this.shop = false;
          }
          else if(this.menu === true){
                this.container = true;
                this.shop = false
              }

        }else{
          if(this.menu === true && this.container === true){
            this.shop = false;
          }

        }
       },
       windowSize: function(width){

             if(width > 768){
                 this.menu = false;
                 this.shop = false;
                 this.container = false;
               }

       },
       refreshNav: function(value){
         this.upHere2 = value;
         this.upHere3 = value;
         this.openClose = value;
         this.dropDSmall(value);
       },
       getCategories: function(){
           // for scope of this within axios
           let self = this;

             axios.post('/categoryshowclient'
               )
              .then((res) => {
                console.log(res);
                if(res.data.categories != undefined && res.data.categories.length ){
                   self.categories = res.data.categories;
                   self.noCategory = true;

                 }else{
                   self.noCategory = false;
                 }

             }).catch((error) => {
               // error handling function with error, name axios is used for, and this.
              errorHandle(error, 'Product Get Categories', self);
             })
         },
         getProductsCount: function(){
             // for scope of this within axios
             let self = this;

               axios.post('/showcountproductscategory'
                 )
                .then((res) => {
                  if(res.data.productscount != undefined && res.data.productscount.length){
                     self.productsCount = res.data.productscount;
                     self.noProduct = true;
                     console.log(res.data);
                     // self.removeCategoryWithNoProduct();

                   }else{
                     self.noProduct = false;
                   }

               }).catch((error) => {
                   // error handling function with error, name axios is used for, and this.
                  errorHandle(error, 'Get Products', self);
               })
           },
      }

}

</script>
<style>

/* CSS for Field Jobs Tracker Navigation Bar Logged in */

.nav-sign-jobs {
  position: relative;
  display: inline-block;
  padding: 4px 4px 1px 4px;
}
.nav-sign-arrow-1 {
  position: absolute;
  top: -3px;
  left: 2%;
  color: #3490dc;
  font-size: 20px;
}
.nav-sign-arrow-2 {
  position: absolute;
  bottom: -6px;
  left: 66%;
  color: #3490dc;
  font-size: 20px;
}
/* .nav-field {
  padding-right: 2px;
  padding-top: 1px;
  border-top: solid 2px #3490dc;
}
.nav-tracker {
  border-bottom: solid 2px #3490dc;
} */
.chat-news-icon-container {
  position: absolute;
  top: 30px;
  right: 10%;
  display: inline-block;
}
.chat-news-icon-wrapper {
  width: 30px;
  height: 30px;
  text-align: center;
  position: relative;
  cursor: pointer;
}
.chat-news-icon {
  position: relative;
  top: 4px;
  left: 0;
  margin: 0 auto;
  color: #FFFFFF;
  font-size: 25px;
}
.comments-count {
  position: relative;
  top: -26px;
  left: -1px;
  margin: 0 auto;
  color: rgb(215, 215, 215);
  font-size: 16px;
  text-shadow: 1px 1px 1px rgba(0,0,0, .8);
}

</style>
