<template>
    <div :class="templateName + '-category-shop-container'">
      <!-- is the trail of pages shown -->
      <h5 :class="[templateName + '-category-shop-trail',show ? templateName + '-animateShopTrail' : '' ]" >
        Shop > <span :class="templateName + '-shop-trail-span'">{{ category }}</span>
      </h5>
      <div :class="[templateName + '-category-caption-wrapper', show ? templateName + '-animateCategoryCaptionP' : '' ]"  v-if="categoryInfo.length">
        <!-- span are borders that wrap caption -->
        <span :class="[templateName + '-category-border-top', show ? templateName + '-animateCategoryCaption' : '']"></span>
        <span :class="[templateName + '-category-border-left', show ? templateName + '-animateCategoryCaption' : '']" ></span>
        <p :class="templateName + '-category-caption'"   v-if="categoryInfo[0].caption != null">
          {{ categoryInfo[0].caption }}
        </p>
        <!-- if caption == null then show the else element -->
        <p v-else>Thanks for supporting us! </p>
        <!-- span are borders that wrap caption -->
        <span :class="[templateName + '-category-border-bottom', show ? templateName + '-animateCategoryCaption' : '' ]"></span>
        <span :class="[templateName + '-category-border-right', show ? templateName + '-animateCategoryCaption' : '']"></span>
      </div>
      <category-products></category-products>
      <category-products
        v-bind:products="products"
        v-bind:groups="productGroups"
        v-bind:product-default-image="productDefaultImage"
        >
      </category-products>
    </div>
</template>



    <script>

    import CategoryProducts from './categoryproducts.vue';

    export default {
    components: {
    'category-products': CategoryProducts,
    },
  data(){
    return{
      products: [],
      productGroups: [],
      productDefaultImage: [],
      categoryInfo: [],
      show: false,
    }
  },
  mounted(){
    // delay method for page loader activity
    setTimeout(() => {
        this.showProductInfo();
    },2000);
  },
  computed: {
    category: function(){
      return this.$route.params.category;
    },
    templateName: function(){
      if(this.$store.state.templateView){
         return this.$store.state.templateView;
       }
    }
  },
  watch:{
    category: function(newCat,oldCat){
      if(newCat){
        this.showProductInfo();
      }
    },
      products: function(oldProd, newProd){
        if(newProd){
          // set show to true to trigger animateCategory a binded class
          this.show = true;
          console.log('categoryContainer');
          let self = this;
          setTimeout(function(){
            // reset show to false that removes animateCategory binded class
            self.show = false;
          }, 3000);
        }
      }
  },
  methods: {
    showProductInfo: function(){
        // for scope of this within axios
        let self = this;
        console.log('called');
          axios.post('/showcategoryproducts',
          {
            category_id: self.$store.state.products.categoryId,
          }
          )
           .then((res) => {
             if(res.data.success){
                self.categoryInfo = res.data.category;
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
