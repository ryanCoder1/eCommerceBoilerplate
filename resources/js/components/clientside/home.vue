<template>
    <div class="home-page">

      <!--
          components that make the home page
          if loader is true meaning all components are loaded
      -->
        <slide-featured
          v-if="slideFeaturedUse">
        </slide-featured>
        <!-- don't load this div elements until slide-featured is loaded (top of page) -->
        <!-- <div v-if="loader"> -->
          <about-home
            v-if="loadAbout">
          </about-home>
          <products-home
            v-if="loadProducts">
          </products-home>
          <banner-one
            v-if="bannerUse"
            v-bind:ban-num="1">
          </banner-one>
          <slide-specials
            v-if="slideSpecialsUse">
          </slide-specials>
          <banner-two
            v-if="bannerUse"
            v-bind:ban-num="0">
          </banner-two>
        <!-- </div> -->
    </div>
</template>



<script>
import SlideSpecials from './slidespecials.vue';
import SlideFeatured from './slidefeatured.vue';
import ProductsHome from './productshome.vue';
import BannerOne from './bannerhome.vue';
import BannerTwo from './bannerhome.vue';
import AboutHome from './abouthome.vue';
import LoadDots from '../pages/loaddots.vue';

export default {
  components: {
    'slide-specials': SlideSpecials,
    'slide-featured': SlideFeatured,
    'products-home': ProductsHome,
    'banner-one': BannerOne,
    'banner-two': BannerTwo,
    'about-home': AboutHome,

  },
  data(){
    return{
      slideSpecialsUse: false,
      slideFeaturedUse: false,
      loadProducts: false,
      bannerUse: false,
      loadAbout: false,
    }
  },
  created(){
    // once featuredLoad is done, update loader to true and show rest of home components.
    this.$root.$on('featuredLoad', (value) => {
      this.loader = true;
      console.log(this.loader);
    })
  },
  mounted(){
    this.getFeaturedUse();
    this.getSpecialsUse();

    // delay the load of Products in home component
    // so featured slide show loads first
    let self = this;
    setTimeout(function(){
      self.loadProducts = true;
      self.getBannerUse();
      self.getAboutUse();
    },3000);
  },
  methods: {
    getAboutUse: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/aboutuseclient'
            )
           .then((res) => {
             if(res.data.using != undefined && res.data.using.length ){
                 if(res.data.using[0].using == 0){
                   self.loadAbout = false;
                 }else{
                   self.loadAbout = true;
                 }
              }
          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'About show load', self);
          })
      },
      getFeaturedUse: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/featureduseclient'
              )
             .then((res) => {
               if(res.data.using != undefined && res.data.using.length ){
                   if(res.data.using[0].using == 0){
                     self.slideFeaturedUse = false;
                   }else{
                     self.slideFeaturedUse = true;
                   }
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Featured slide show load', self);
            })
        },
        getSpecialsUse: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/specialsuseclient'
                )
               .then((res) => {
                 if(res.data.using != undefined && res.data.using.length ){
                     if(res.data.using[0].using == 0){
                       self.slideSpecialsUse = false;
                     }else{
                       self.slideSpecialsUse = true;
                     }
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Specials slide show load', self);
              })
          },
          getBannerUse: function(){
              // for scope of this within axios
              let self = this;

                axios.post('/banneruseclient'
                  )
                 .then((res) => {
                   if(res.data.using != undefined && res.data.using.length ){
                       if(res.data.using[0].using == 0){
                         self.bannerUse = false;
                       }else{
                         self.bannerUse = true;
                       }
                    }
                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Banner use load', self);
                })
            },
    }

}


</script>
<style>

</style>
