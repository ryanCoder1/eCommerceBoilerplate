<template>
        <div class="side-bar-menu-container" v-bind:style="{ minHeight: sideBarHeight + 700 + 'px' }">
          <div class="side-bar-head-container">
            <span class="side-bar-close" v-on:click="closeMenuContainer()">
              <i class="fa fa-angle-left"></i>
            </span>
            <span class="side-bar-head-1">eCommerce</span>
            <span class="side-bar-head-2">Dashboard</span>
          </div>
          <ul class="side-bar-ul">
            <li>
              <i class='fa fa-tachometer-alt'></i>
              <router-link :to="{ path: '/dashboard' }"><a>Dashboard</a></router-link>
            </li>
            <li v-on:click="dropMenu($event, null)">
              <i class='far fa-user'></i>
              <a href="#">Personal Info</a>
              <i class="fa fa-caret-down i-visited"></i>
              <ul class="drop-down-uls" v-show="showPersInfo">
                <li><router-link :to="{ path: '/dashboard/phonenumberinfo' }"><a  v-on:click="closeMenuContainer()">Phone Number</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/emailaddressinfo' }"><a  v-on:click="closeMenuContainer()">Email Address</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/socialmediainfo' }"><a  v-on:click="closeMenuContainer()">Social Media</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/banner' }"><a  v-on:click="closeMenuContainer()">Banner</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/about' }"><a  v-on:click="closeMenuContainer()">About</a></router-link></li>
              </ul>
            </li>
            <li v-on:click="dropMenu($event, null)">
              <i class='fa fa-folder-open'></i>
              <a href="#">Categories</a>
              <i class="fa fa-caret-down i-visited"></i>
              <ul class="drop-down-uls" v-show="showProdCat">
                <li><router-link :to="{ path: '/dashboard/productcategorycreate' }"><a  v-on:click="closeMenuContainer()">Category create</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/productcategoryedit' }"><a  v-on:click="closeMenuContainer()">Category edit</a></router-link></li>
              </ul>
            </li>
            <li v-on:click="dropMenu($event, null)">
                <i class='fa fa-shopping-basket'></i>
                <a href="#">Products</a>
                <i class="fa fa-caret-down i-visited"></i>
                <ul class="drop-down-uls" v-show="showProducts">
                  <li><router-link :to="{ path: '/dashboard/productscreate' }"><a  v-on:click="closeMenuContainer()">Product create</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/productgroups' }"><a  v-on:click="closeMenuContainer()">Product groups</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/productimages' }"><a  v-on:click="closeMenuContainer()">Product images</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/productsedit' }"><a  v-on:click="closeMenuContainer()">Product edit / view</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/productdefaultimage' }"><a  v-on:click="closeMenuContainer()">Product default image</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/productsshowhomepage' }"><a  v-on:click="closeMenuContainer()">Products on home page</a></router-link></li>
                  <li><router-link :to="{ path: '/dashboard/orderdimensions' }"><a  v-on:click="closeMenuContainer()">Order Dimensions</a></router-link></li>
                </ul>

            </li>

            <!-- <li>
              <i class='fa fa-folder-open'></i>
              <router-link :to="{ path: '/dashboard/categories' }"><a  v-on:click="closeMenuContainer()">Categories</a></router-link>
            </li> -->
            <li v-on:click="dropMenu($event, null)">
              <i class='fa fa-images'></i>
              <a href="#">Slides</a>
              <i class="fa fa-caret-down i-visited"></i>
              <ul class="drop-down-uls" v-show="showSlides">
                <li><router-link :to="{ path: '/dashboard/slidefeatured' }"><a  v-on:click="closeMenuContainer()">Featured</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/slidespecials' }"><a  v-on:click="closeMenuContainer()">Specials</a></router-link></li>
                <li><router-link :to="{ path: '/dashboard/slidebestsellers' }"><a  v-on:click="closeMenuContainer()">Best sellers</a></router-link></li>
              </ul>
            </li>
            <li>
              <i class='far fa-user'></i>
              <router-link :to="{ path: '/dashboard/viewcustomers' }"><a  v-on:click="closeMenuContainer()">View Customers</a></router-link>
            </li>
            <li>
              <i class='fa fa-cart-plus'></i>
              <router-link :to="{ path: '/dashboard/vieworders' }"><a  v-on:click="closeMenuContainer()">View Orders</a></router-link>
            </li>
            <li>
              <i class='fa fa-credit-card'></i>
              <router-link :to="{ path: '/dashboard/viewpayments' }"><a  v-on:click="closeMenuContainer()">View Payments</a></router-link>
            </li>
            <li>
              <i class='fa fa-user'></i>
              <router-link :to="{ path: '/dashboard/dashboardusers' }"><a  v-on:click="closeMenuContainer()">Dashboard users</a></router-link>
            </li>
            <li>
              <i class='fa fa-share-square'></i>
              <router-link :to="{ path: '/dashboard/logout' }"><a  v-on:click="closeMenuContainer()">Logout</a></router-link>
            </li>
          </ul>
        </div>
</template>



<script>

export default {

    props: [
      'sideBarHeight',
      'subMenu',
    ],
    data(){
      return{
        showProducts: false,
        showProdCat: false,
        showSlides: false,
        showPersInfo: false,
        subMenuChoice: [
          'Personal Info',
          'Categories',
          'Products',
          'Slides',

        ]
      }

  },
  mounted(){
    let self = this;
    setTimeout(function(){
      console.log('mounted ' + self.$props.subMenu);
        self.dropMenu(null, self.$props.subMenu);
    },500);

  },
  methods: {
    closeMenuContainer: function(){
      this.$root.$emit('closeMenuContainer', true);
    },
    dropMenu: function(event, sub_menu){

      let menuLi;
      if(event != null){
        menuLi = event.path[0].innerText;
      }else{
        menuLi = sub_menu;
      }

      let anchor = document.getElementsByTagName("a");
      let next_sibling = null;
      for(let i = 0; i < anchor.length; i++){
        if(anchor[i].outerText.match(menuLi)){
          console.log(anchor[i]);
          next_sibling = anchor[i].nextElementSibling;
          console.log(next_sibling);
        }
      }
      // // close all menus not using
      // method rotateArrowBack's parameter is the index in the list element array
        for(let i = 0; i < this.subMenuChoice.length; i++){
          if(this.subMenuChoice[i].match(this.subMenu) == null){
              let menu = this.subMenuChoice[i];
              switch(menu) {
                case 'Personal Info':
                    this.showPersInfo = false;
                    // rotate all arrows back to 0 degrees
                    this.rotateArrowBack(0);
                break;
                case 'Categories':
                    this.showProdCat = false;
                    // rotate all arrows back to 0 degrees
                    this.rotateArrowBack(1);
                break;
                case 'Products':
                    this.showProducts = false;
                    // rotate all arrows back to 0 degrees
                    this.rotateArrowBack(2);
                break;
                case 'Slides':
                    this.showSlides = false;
                    // rotate all arrows back to 0 degrees
                    this.rotateArrowBack(3);
                break;
                default:
                  return false;
                break;
              }
          }
       }


      switch(menuLi) {
        case 'Personal Info':
            this.showPersInfo = !this.showPersInfo;
                if(this.showPersInfo){
                  this.rotateArrow(next_sibling, 180);
                }else{
                  this.rotateArrow(next_sibling, 0);
                }
        break;
        case 'Categories':
            this.showProdCat = !this.showProdCat;
                if(this.showProdCat){
                  this.rotateArrow(next_sibling, 180);
                }else{
                  this.rotateArrow(next_sibling, 0);
                }
        break;
        case 'Products':
            this.showProducts = !this.showProducts;
                if(this.showProducts){
                  this.rotateArrow(next_sibling, 180);
                }else{
                  this.rotateArrow(next_sibling, 0);
                }
        break;
        case 'Slides':
            this.showSlides = !this.showSlides;
                if(this.showSlides){
                  this.rotateArrow(next_sibling, 180);
                }else{
                  this.rotateArrow(next_sibling, 0);
                }
        break;
        default:
          return false;
        break;
      }
    },
    rotateArrow: function(i, deg){

      if(i != null){
        i.style.webkitTransformOrigin = '50% 50% 0';
        i.style.mozTransformOrigin    = '50% 50% 0';
        i.style.msTransformOrigin     = '50% 50% 0';
        i.style.oTransformOrigin      = '50% 50% 0';
        i.style.transformOrigin = '50% 50% 0';
        i.style.webkitTransform = 'rotate('+deg+'deg)';
        i.style.mozTransform    = 'rotate('+deg+'deg)';
        i.style.msTransform     = 'rotate('+deg+'deg)';
        i.style.oTransform      = 'rotate('+deg+'deg)';
        i.style.transform       = 'rotate('+deg+'deg)';
      }

    },
    rotateArrowBack: function(m){
          console.log(m);
      let i = document.getElementsByClassName('i-visited');
          if(i[m] != null){
            i[m].style.webkitTransformOrigin = '50% 50% 0';
            i[m].style.mozTransformOrigin    = '50% 50% 0';
            i[m].style.msTransformOrigin     = '50% 50% 0';
            i[m].style.oTransformOrigin      = '50% 50% 0';
            i[m].style.transformOrigin = '50% 50% 0';
            i[m].style.webkitTransform = 'rotate(0deg)';
            i[m].style.mozTransform    = 'rotate(0deg)';
            i[m].style.msTransform     = 'rotate(0deg)';
            i[m].style.oTransform      = 'rotate(0deg)';
            i[m].style.transform       = 'rotate(0deg)';
          }

    },
  }


}


</script>
<style>


</style>
