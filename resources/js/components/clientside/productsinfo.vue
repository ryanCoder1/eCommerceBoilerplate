<template>
    <div>
      <router-link :to="{ name: 'Product', params: {product: product.title} }">
        <a
        v-on:click="saveProductIdStore(product.id)">
          <div :class="[templateName + '-block', templateName + '-product-image-block']">
            <img v-if="!groupImageVis && product.image_name != null" :class="templateName + '-product-image'" :src="'../storage/images/' + product.image_path + '/' + product.image_name" alt="product image">
            <img v-if="groupImageVis && groupImage.length" :class="templateName + '-product-image'" :src="'../storage/images/' + groupImage[0].image_path + '/' + groupImage[0].image_name" alt="product image">
            <img v-if="!groupImageVis && product.image_name == null" :class="templateName + '-product-image'" :src="'../storage/images/' + productDefaultImage[0].image_path + '/' + productDefaultImage[0].image_name" alt="product image">
            <div :class="templateName + '-product-sales-wrapper'">
              <!-- sale sign in product element -->
              <div :class="templateName + '-product-sales-banner'" v-if="product.sale_price > 0 && product.sale_price != null">
                <span :class="templateName + '-dollar-sign-sale'">$</span>{{ product.sale_price }}<small> off</small>
              </div>
            </div>
          </div>
          <div :class="[templateName + '-block', templateName + '-product-data']">
              <h5 :class="templateName + '-title'">{{ product.title }}</h5>
              <br/>
              <p :class="templateName + '-price'">
                <span :class="templateName + '-dollar-sign'">$</span><span :class="templateName + '-total'">{{ product.price }}</span>
              </p>
              <div :class="templateName + '-slide-color-container'">
                <div
                :class="templateName + '-slide-bg-color-hex' "
                v-bind:style="[group.product_id == product.id && group.color.match(product.color) == null ? {backgroundColor: group.color} : {display: 'none'} ]"
                v-for="(group, index) in groups[productIndex]" :key="index"
                v-on:mouseover="showGroupProductImage(product.id, group.color)"
                v-on:mouseout="showProductImage(false)">
                </div>
                <p :class="templateName + '-product-msg'" v-if="productImageVis">Loading image</p>
                <p :class="templateName + '-product-msg'"  v-if="noProductImageVis">Image not available</p>
              </div>
            </div>
        </a>
      </router-link>
    </div>
</template>



<script>


export default {


  props: {
    'product': Object,
    'groups': Array,
    'productDefaultImage': Array,
    'productIndex': Number,


  },
  data(){
    return{
      totalPrice: 0,
      uniqueGroups: [],
      groupImageVis: false,
      groupImage: [],
      productImageVis: false,
      noProductImageVis: false,

    }
  },
  computed: {
    templateName: function(){
      if(this.$store.state.templateView){
         return this.$store.state.templateView;
       }
    }
  },
  methods: {
    saveProductIdStore: function(id){
      // send product id to products store for product page
      this.$store.dispatch('products/getProductId', id);
    },
    offNoProductImageVis: function(){
      let self = this;
      setTimeout(function(){
        self.noProductImageVis = false;
      }, 2000)
    },
    showProductImage: function(bool){
      this.groupImageVis = false;
    },
    showGroupProductImage: function(id, color){
        this.groupImage = [];
        this.productImageVis = true;
        // for scope of this within axios
        let self = this;

          axios.post('/showgroupproductimage',
          {
            product_id: id,
            color: color,
          }
           )
           .then((res) => {

              if(res.data.groupImage != undefined && res.data.groupImage.length){
                  self.groupImage = res.data.groupImage;
                  self.groupImageVis = true;
                  setTimeout(function(){
                    self.productImageVis = false;
                  },1000);
                }else{
                  setTimeout(function(){
                    self.productImageVis = false;
                    self.noProductImageVis = true;
                  },1000);

                  self.offNoProductImageVis();
                }
          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Featured Save', self);
          })

      },

  }
}

  </script>
  <style >

  </style>
