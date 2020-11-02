<template>
      <div id="dashboardProductsShowContainer" class="dashboard-page-containers">
         <h3>Products Show on Home page</h3>
          <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to edit.</h4>
            <products-list
            v-bind:products="products">
            </products-list>
      </div>
</template>



<script>

import ProductsList from './productslist.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'products-list': ProductsList,
    },
    data(){
      return{
        noProduct: false,
        products: [],
      }

  },
  created(){
    this.$root.$on('adjustSideBarHeight', (value) => {
        this.templateHeight();
    })
  },
  mounted(){
    this.getProducts();
    this.pageOpen();
  },

  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsShowContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    getProducts: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/productsretrieve',
          {
            Authorization:  this.$store.state.admin.currentAdmin.token
          },
            )
           .then((res) => {
             if(res.data.products != undefined && res.data.products.length ){
                self.products = res.data.products;
                self.noProduct = true;

              }else{
                self.noProduct = false;
              }

          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Product Get in Edit', self);
          })
      },

  }


}


</script>
<style>


</style>
