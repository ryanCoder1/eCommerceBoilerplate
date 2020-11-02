<template>
      <div id="categoryContainer" class="products-container" v-bind:class="{animateCategory: show }">
            <ul  class="products-ul" >
              <li class="products-li" v-for="(product, index) in products" :key="index">
                <products-info
                  v-bind:product="product"
                  v-bind:groups="groups"
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
  props: {
    'products': Array,
    'groups': Array,
    'productDefaultImage': Array,
  },
  data(){
    return{
      show: false,
    }
  },
  mounted(){



  },
  watch: {
    products: function(oldProd, newProd){
      if(newProd){
        // set show to true to trigger animateCategory a binded class
        this.show = true;
          console.log('categoryWrapper');
          let self = this;
          setTimeout(function(){
            // reset show to false that removes animateCategory binded class
            self.show = false;
          }, 3000);
      }
    }
  },
  methods: {


  }
}

  </script>
<style >


</style>
