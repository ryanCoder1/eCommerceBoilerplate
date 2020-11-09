<template>
      <div id="dashboardProductsGroupsContainer" class="dashboard-page-containers">
         <h3>Product Groups</h3>
          <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to create a product group.</h4>
            <form id="dashboardProductsGroups" class="form" v-if="noProduct">

                      <label class="col-12 mx-auto">
                        <div class="inputContainer">
                          <p>
                            <select class="form-control formInput" name="state" v-on:change="clearErrors()" v-model="form.product_id">
                            <option value="" disabled>Choose Product</option>
                            <option :value="product.id" v-for="(product, index) in products">{{ product.title }}</option>
                         </select>
                        <span class="labelStyle">Products</span>
                       </p>
                       </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                       <div class="inputContainer">
                         <p>
                           <input class="form-control" v-bind:class="{formInput: isActive}"  type="color" v-on:change="displayColorHex()" v-model="form.color" />
                           <span class="labelStyle">Product color (if necessary)</span>
                         </p>
                       </div>
                     </label>

                     <div class="groups-bg-color-hex ml-4" v-bind:style="{backgroundColor: colorHex }" v-if="colorHexCheck"></div>

                     <label class="col-12 mx-auto" v-if="noDimensions">
                       <div class="inputContainer">
                         <p>
                           <select class="form-control formInput" name="state" v-on:change="clearErrors()" v-model="form.dimension_name">
                           <option value="" disabled>Choose dimension</option>
                           <option :value="dimension.dimension" v-for="(dimension, index) in dimensions">{{ dimension.dimension }}</option>
                        </select>
                       <span class="labelStyle">Dimensions (if necessary)</span>
                      </p>
                      </div>
                    </label>
                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"  type="text"  v-model="form.dimension"/>
                         <span class="labelStyle" >Dimensions (This field will save for future dimension options) (if necessary)</span>
                       </p>
                     </div>
                     </label>

                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.in_stock"/>
                         <span class="labelStyle" >In Stock  (if necessary but can be tracked for proper notifications)</span>
                       </p>
                     </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.price"/>
                         <span class="labelStyle" >Price</span>
                       </p>
                     </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.sale_price" />
                         <span class="labelStyle" >Sale Price (if necessary)</span>
                       </p>
                     </div>
                     </label>
                     <img class="image-group ml-4 mb-2" v-if="url" :src="url" />
                     <div class="input-container-file">
                       <p>
                         <input class="inputfile" id="file" ref="file"  type="file" v-on:change="handleFile()" />
                         <label for="file">
                           <i class="fa fa-upload file-icon"></i>
                           Group Image Upload
                         </label>
                         <i class="fa fa-check text-success check-icon" v-if="imageChosen"></i>
                       </p>
                     </div>
                     <!-- Error/Success messages from api -->
                     <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                     <p class="success-msg" v-if="success != null">{{ success }}</p>


                    <p class="dashboard-page-btn" v-on:click="saveGroups()">
                      <input type="button" value="Save Product Info" v-if="!loading"/>
                      <load-dots
                       v-if="loading"
                       v-bind:loading-dots="loadingDots"
                       >
                     </load-dots>
                    </p>
             </form>
        </div>
</template>



<script>
import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        noProduct: false,
        noDimensions: false,
        dimensions: [],
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        imageChosen: false,
        isActive: false,
        products: [],
        colorHexCheck: false,
        colorHexCheckFail: false,
        colorHex: null,
        form: {
          product_id: null,
          color: null,
          dimension: null,
          dimension_name: null,
          in_stock: null,
          price: null,
          sale_price: null,
          Authorization:  this.$store.state.admin.currentAdmin.token,
        },
        image_file: '',
        url: null,
        imageChosen: false,

      }

  },
  mounted(){

    // retrieve categories for products on page load
     this.getProducts();
     this.getDimensions();
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();

  },
  watch: {
    noProduct: function(newNoProduct, oldNoProduct){
      // run function to get height of template to send to parent sidebarmenu
      if(newNoProduct){
        let self = this;
        setTimeout(function(){
           self.templateHeight();
        }, 500);

      }
    }
  },
  methods: {
    handleFile: function(){
      // test if file is an image before saving it.
      if(!this.imageRequired()){
        return false;
      }
      this.image_file = this.$refs.file.files[0];
      this.url = URL.createObjectURL(this.$refs.file.files[0]);
      this.imageChosen = true;
      let self = this;
      setTimeout(function(){
          self.templateHeight();
      }, 100);

    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    clearErrors: function(){
      this.errors = null;
      this.success = null;
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsGroupsContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    validation: function(){
        if(this.$data.form.product_id == null){
              this.errors = 'A product must be chosen for product group.';
              return false;
        }
        else if(this.$data.form.product_id != null && this.$data.form.size == null && this.$data.form.dimension == null && this.$data.form.dimension_name == null && this.$data.form.price == null && this.$data.form.sale_price == null && this.$data.form.in_stock == null){
              this.errors = 'Can not send through an empty product group.';
              return false;
        }
        else if(this.$data.form.price == null){
              this.errors = 'A product price is needed.';
              return false;
        }
        else {
            return true;
        }

    },
    displayColorHex: function(){
        // display the color of input color chosen
        this.colorHexCheck = true;
        this.colorHex = this.$data.form.color;

    },
    imageRequired: function(i){
      // if file type isn't an image clear image
      let file = this.$refs.file.files[0];
      if(file['type'].split('/')[0] === 'image'){
          return true;
      }else{

          this.clearImage(i);
          this.errors = 'File must be an image to upload.';
          return false;
      }
    },
    clearImage: function(i){
      this.imageChosen = false;
      this.$refs.file.value = '';
      this.url = null;

    },
    refreshFields: function(){
      this.$data.form.product_id = null;
      this.$data.form.color = null;
      this.$data.form.dimension = null;
      this.$data.form.dimension_name = null;
      this.$data.form.in_stock = null;
      this.$data.form.price = null;
      this.$data.form.sale_price = null;
      this.$data.isActive = false;
      this.colorHex = null;
      this.colorHexCheck = false;
      this.clearImage();
    },
      saveGroups: function(){

        if(!this.validation()){
            return false;
          }
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
              fd.append('file',this.image_file)
              fd.append('product_id',this.$data.form.product_id)
              fd.append('color',this.$data.form.color)
              fd.append('dimension',this.$data.form.dimension)
              fd.append('dimension_name',this.$data.form.dimension_name)
              fd.append('in_stock',this.$data.form.in_stock)
              fd.append('price',this.$data.form.price)
              fd.append('sale_price',this.$data.form.sale_price)
              fd.append('Authorization', this.$store.state.admin.currentAdmin.token)

              axios.post('/productgroups',fd,
              {
               headers: {
                        'Content-Type': 'multipart/form-data',
                        }
               })
             .then((res) => {
                console.log(res);
               if(res.data.success){

                  this.refreshFields();
                  self.loading = false;
                  self.success = 'Product Group was saved successfully!';
                  self.getDimensions();
             }
            }).catch((error) => {
              console.log(error);
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Groups Save', self);
            })

        },
        getProducts: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/productsretrieve', {Authorization:  this.$store.state.admin.currentAdmin.token},
                )
               .then((res) => {
                 console.log(res);
                 if(res.data.products != undefined && res.data.products.length ){
                    self.products = res.data.products;
                    self.noProduct = true;

                  }else{
                    self.noProduct = false;
                  }

              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Product Get Groups', self);
              })
          },
          getDimensions: function(){
              // for scope of this within axios
              let self = this;

                axios.post('/dimensionsretrieve', {Authorization:  this.$store.state.admin.currentAdmin.token},
                  )
                 .then((res) => {
                   console.log(res);
                   if(res.data.dimensions != undefined && res.data.dimensions.length ){
                      self.dimensions = res.data.dimensions;
                      self.noDimensions = true;

                    }else{
                      self.noDimensions = false;
                    }

                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Product Get Dimensions', self);
                })
            }
    }


}


</script>
<style scoped>

.groups-bg-color-hex {
  width: 100px;
  height: 30px;
  border-radius: 50%;
  -webkit-border-radius: 50%;
  -moz-border-radius: 50%;
  box-shadow: 0 4px 10px 1px rgba(0,0,0, .2);
}
.image-group {
  width: 280px;
  height: 280px;

}
</style>
