<template>
        <div id="productContainer" class="product-container">
          <div class="product-title">{{ product.title }}</div>
            <ul class="product-uls ul-info"  v-if="!deletedProduct" >
              <li>Code<br/> {{ product.product_code == 'null' ? '' : product.product_code }}</li>
              <li class="product-edit-links" v-on:click="showInfo()">Info +</li>
              <li class="product-edit-links" v-on:click="showGroups()">Groups +</li>
              <li class="product-edit-links" v-on:click="showImages()">Images +</li>
            </ul>
            <ul class="product-uls ul-image" v-if="!deletedProduct" >
              <li>
                <!-- main product image -->
                  <img v-if="product.image_name != null && url == null" :src="'../storage/images/' + product.image_path + '/' + product.image_name"/>
                  <img v-else-if="url != null" :src="url"/>
                  <img v-else-if="productDefaultImage.image_name != null && product.image_name == null && url == null " :src=" '../storage/images/' + productDefaultImage.image_path + '/' + productDefaultImage.image_name"/>

                 <div class="dashboard-edit-btn my-2" v-on:click="productEditImage()">
                   <input type="button" :value="product.image_name != '' || url != null ? 'Edit image' : 'Add image'"/>
                </div>
                <product-edit-main-image
                  v-if="mainImageVis"
                  v-bind:product="product"
                  v-bind:url-parent="urlParent"
                  >
                </product-edit-main-image>
               </li>
            </ul>
            <div>
              <div class="dashboard-full-width-btn my-2" v-if="!assureDelete && !deletedProduct" v-on:click="assureProductDelete()">
                <input type="button"   value="Delete Product"/>
             </div>
              <!-- delete btn -->
              <div class="dashboard-full-width-btn bg-primary my-2" v-if="assureDelete && !deletedProduct" v-on:click="productDelete(product.id)">
                <input type="button"   value="Click again to be sure!" v-if="!loading"/>
                <load-dots
                 v-if="loading"
                 v-bind:loading-dots="loadingDots"
                 >
               </load-dots>
             </div>
             <div class="index-text index-text-deleted-product" v-if="deletedProduct" >Product Deleted!</div>
            </div>
            <product-edit-info
              v-if="productInfoVis"
              v-bind:product="product"
            >
          </product-edit-info>
            <product-edit-groups
              v-if="productGroupsVis"
              v-bind:product-styles="productStyles"
            >
            </product-edit-groups>

              <product-edit-images
                v-if="productImagesVis"
                v-bind:product-images="productImages"
              >
              </product-edit-images>

        </div>
</template>



<script>
import LoadDots from '../pages/loaddots.vue';
import ProductEditImages from './productseditimages.vue';
import ProductEditGroups from './productseditgroups.vue';
import ProductEditInfo from './productseditinfo.vue';
import ProductEditMainImage from './producteditmainimage.vue';
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';
export default {

    components: {
      'product-edit-images': ProductEditImages,
      'product-edit-groups': ProductEditGroups,
      'product-edit-info': ProductEditInfo,
      'product-edit-main-image': ProductEditMainImage,
      'load-dots': LoadDots,
    },
    props: {
      'product': Object,
      'productStyles': Array,
      'productImages': Array,
      'productDefaultImage': Object,
    },
    data(){
      return{
        productImagesVis: false,
        productGroupsVis: false,
        productInfoVis: false,
        mainImageVis: false,
        loading: false,
        loadingDots: true,
        urlParent: null,
        url: null,
        assureDelete: false,
        deletedProduct: false,
      }

  },
  created(){
    this.$root.$on('closeImages', (value) =>{
       this.productImagesVis = value;
    }),
    this.$root.$on('closeGroups', (value) =>{
       this.productGroupsVis = value;
    }),
    this.$root.$on('closeInfo', (value) =>{
       this.productInfoVis = value;
    }),
    this.$root.$on('closeMainImage', (value) =>{
       this.mainImageVis = value;
    }),
    this.$root.$on('imageUrl', (value) =>{
       this.urlParent = value;
       this.url = value;
    })
  },
  mounted(){

  },
  watch: {
      product: function(newProduct, oldProduct){
          if(newProduct){
            this.url = null;
          }
      }
  },
  methods: {
    anchorPage: function(id){
      anchorsPage(id);
    },
    productEditImage: function(){
        this.mainImageVis = true;
    },
    showInfo: function(){
        this.productInfoVis = true;
    },
    showGroups: function(){
        this.productGroupsVis = true;
    },
    showImages: function(){
        this.productImagesVis = true;
    },
    assureProductDelete: function(){
        this.assureDelete = true;
    },
    deletedProductHandle: function(){
        let self = this;
        setTimeout(function(){
          self.deletedProduct = false;
          self.refreshProduct();
          self.anchorPage('productContainer');
        },2000);
    },
    refreshProduct: function(){
      this.$root.$emit('refreshProduct', true);
    },
    productDelete: function(){

        // for scope of this within axios
        let self = this;
        this.loading = true;

          axios.post('/productdelete',
          {
            Authorization: self.$store.state.admin.currentAdmin.token,
            product_id: self.product.id
          }
          )
           .then((res) => {
              if(res.data.success){
                  self.loading = false;
                  self.deletedProduct = true;
                  self.deletedProductHandle();
              }
          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Product Delete', self);
          })

      },
  }


}


</script>
<style scoped>
.product-container {
  width: 100%;
  max-width: 500px;
  padding: 0 10px 10px 10px;
  margin: 50px auto;
  background-color: #FFFFFF;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
  text-align: center;
  position: relative;
}
.product-uls {
  margin-top: 15px;
  display: inline-block;
  list-style: none;
  vertical-align: top;

}
.ul-info {
  text-align: left !important;

}
.ul-info li {
  font-size: 18px;
  margin-bottom: 15px;
  color: #213B50;
}
.ul-image {
  margin-right: auto;
}
.product-uls img {
  width: 220px;
  height: 225px;
  display: block;
  background-color: #FFFFFF;
  border: solid thin rgb(217, 213, 213);
  /* box-shadow: 0 0 20px 1px rgba(0,0,0, .2); */
}
.product-title {
  width: 100%;
  padding: 5px 0;
  font-size: 18px;
  color: #213B50;
  font-weight: bold;
  border-bottom: solid thin rgb(228, 228, 228);
}
.product-edit-links {
  cursor: pointer;
  padding: 5px 10px 5px 5px;
}
.product-edit-links:hover {
  background-color: #f2f2f2;
}
.index-text-delete-product {
  height: 250px;
  padding-top: 50px;

 }
</style>
