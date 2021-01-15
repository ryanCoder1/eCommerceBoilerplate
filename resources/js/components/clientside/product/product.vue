<template>
    <div :class="templateName + '-product-page-container'">
      <div :class="templateName + '-product-info-wrapper'">
        <!-- product ratings is in product-title component -->
        <product-title
        v-bind:product-title="product.title">
        </product-title>

        <div :class="templateName + '-info-image'">
          <img :class="[activeImg ? templateName + '-show-image' : '']" v-if="product.image_name !== undefined && product.image_name != null && colorClicked.image_name == null" :src="'../storage/images/' + product.image_path + '/' + product.image_name" alt="product image">
          <img :class="[activeImg ? templateName + '-show-image' : '']" v-if="colorClicked.image_name != null" :src="'../storage/images/' + colorClicked.image_path + '/' + colorClicked.image_name" alt="product image">
          <img :class="[activeImg ? templateName + '-show-image' : '']" v-if="product.image_name !== undefined && product.image_name == null && colorClicked.image_name == null" :src="'../storage/images/' + productDefaultImage.image_path + '/' + productDefaultImage.image_name" alt="product image">
        </div>
        <!-- product prices with sales and original costs -->
        <product-price
        v-bind:product="product"
        v-bind:color-clicked="colorClicked">
        </product-price>

        <!-- product prices with sales and original costs -->
        <product-in-stock
        v-bind:product="product"
        v-bind:color-clicked="colorClicked">
        </product-in-stock>

        <!-- select a size for product -->
        <product-size
        v-bind:product-groups-size="productGroupsSize"
        v-bind:color-clicked="colorClicked">
        </product-size>

        <!-- the available colors for the size selected -->
        <product-colors
        v-bind:size="size"
        v-bind:product-id="product.id">
        </product-colors>

      </div>
      <product-add
       v-if="Object.keys(this.colorClicked).length !== 0"
       v-bind:color-clicked="colorClicked">
      </product-add>

      <!-- details of product -->
      <product-description
      v-bind:product-description="product.description">
      </product-description>
    </div>
</template>


<script>
import ProductPrice from './productprice.vue';
import ProductInStock from './productinstock.vue';
import ProductColors from './productcolors.vue';
import ProductSize from './productsize.vue';
import ProductTitle from './producttitle.vue';
import ProductDescription from './productdescription.vue';
import ProductAdd from './productadd.vue';

export default {
  components: {
    'product-colors': ProductColors,
    'product-price': ProductPrice,
    'product-in-stock': ProductInStock,
    'product-size': ProductSize,
    'product-title': ProductTitle,
    'product-description': ProductDescription,
    'product-add': ProductAdd,
  },
data(){
  return{
    product: [],
    productGroups: [],
    productGroupsSize: [],
    productDefaultImage: [],
    categoryInfo: [],
    show: false,
    size: '',
    colorClicked: {},
    activeImg: false,
    tempName: null,
  }
},
  created(){
    this.$root.$on('size', (value) => {
      this.size = value;
    }),
    this.$root.$on('colorClicked', (value) => {
      this.activeImg = false;
      this.activeImg = true;
      this.colorClicked = value;
    })
  },
  mounted(){
    // delay method for page loader activity
    setTimeout(() => {
        this.showProductInfo();
    },1000);

  },
  computed: {
    templateName: function(){
      if(this.$store.state.templateView){
         return this.$store.state.templateView;
       }
    }
  },
  methods: {
    addDimensionsInArray: function(){
      this.productGroupsSize.push(this.product);
      console.log(this.productGroupsSize);
    },
    showProductInfo: function(){
        // for scope of this within axios
        let self = this;
        console.log('called');
          axios.post('/showproduct',
          {
            product_id: self.$store.state.products.productId,
          }
          )
           .then((res) => {
             if(res.data.success){
               console.log(res.data);
                self.product = res.data.product[0];
                self.productGroups = res.data.product_styles;
                self.productGroupsSize = res.data.product_styles_size;
                self.productDefaultImage = res.data.product_default_image[0];
                self.productVis = true;
                // self.addDimensionsInArray();

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
