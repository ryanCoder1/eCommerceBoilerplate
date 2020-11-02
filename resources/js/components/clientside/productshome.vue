<template>
    <div  class="products-container">
      <div class="products-client-head">Products</div>
          <ul  class="products-ul">
            <li class="products-li" v-for="(product, index) in products" :key="index">
              <products-info
                v-bind:product="product"
                v-bind:groups="productGroups"
                v-bind:product-default-image="productDefaultImage"
                v-bind:product-index="index"
                >
              </products-info>
            </li>
          </ul>
    </div>
</template>



<script>
import ProductsInfo from './productsinfo.vue';

export default {
  components: {
    'products-info': ProductsInfo,
  },

  data(){
    return{
      products: [],
      productGroups: [],
      productDefaultImage: [],
    }
  },
  mounted(){
    this.showProductInfo();
  },
  methods: {

    showProductInfo: function(){
        // for scope of this within axios
        let self = this;
        console.log('called');
          axios.post('/showproductsclienthome'
            )
           .then((res) => {
             if(res.data.success){
                self.products = res.data.products;
                self.productGroups = res.data.product_styles;
                self.productDefaultImage = res.data.product_default_image;
                self.productVis = true;
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
