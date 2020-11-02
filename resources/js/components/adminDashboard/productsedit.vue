<template>
      <div id="dashboardProductsEditContainer" class="dashboard-page-containers">
         <h3>Products Edit</h3>
          <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to edit.</h4>
          <form id="dashboardProductsEdit" class="form pb-2" v-if="noProduct">

                  <label class="col-12 mx-auto">
                    <div class="inputContainer">
                      <p>
                        <select class="form-control formInput" name="state" v-on:change="showProductInfo()" v-model="product_id">
                        <option value="" disabled>Choose Product</option>
                        <option :value="product.id" v-for="(product, index) in products">{{ product.title }}</option>
                     </select>
                    <span class="labelStyle">Products</span>
                   </p>
                   </div>
                 </label>
           </form>
            <products-edit-product
              v-if="productVis"
              v-bind:product="product"
              v-bind:product-images="productImages"
              v-bind:product-styles="productStyles"
              v-bind:product-default-image="productDefaultImage"
             >
            </products-edit-product>
      </div>
</template>



<script>

import ProductsEditProduct from './productseditproduct.vue';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'products-edit-product': ProductsEditProduct,
    },
    data(){
      return{
        noProduct: false,
        runTempHeight: false,
        product_id: null,
        productVis: false,
        products: [],
        productImages: [],
        productStyles: [],
        productDefaultImage: null,
      }

  },
  created(){
    this.$root.$on('refreshProduct', (value) => {
      this.product_id = null;
      this.productVis = false;
      this.products = [];
      this.getProducts();
    })
  },
  mounted(){
    this.getProducts();
    this.pageOpen();
  },
  watch: {
    runTempHeight: function(newNoProduct, oldNoProduct){
      // run function to get height of template to send to parent sidebarmenu
        console.log(oldNoProduct + ' and ' + newNoProduct);
      if(newNoProduct){
        let self = this;
        setTimeout(function(){
           self.templateHeight();
        }, 500);

      }
   }
  },
  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsEditContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    getProducts: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/productsretrieve', {Authorization:  this.$store.state.admin.currentAdmin.token},
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
      showProductInfo: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/showproduct', {Authorization:  this.$store.state.admin.currentAdmin.token, product_id: this.product_id},
              )
             .then((res) => {
               if(res.data.success){
                  self.product = res.data.product[0];
                  self.productStyles = res.data.product_styles;
                  self.productImages = res.data.product_images;
                  self.productDefaultImage = res.data.product_default_image[0];
                  self.productVis = true;
                  self.runTempHeight = true;
                }

            }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Product Info Get', self);

            })
        },
  }


}


</script>
<style>


</style>
