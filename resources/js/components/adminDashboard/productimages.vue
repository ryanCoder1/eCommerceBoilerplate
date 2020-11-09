<template>
      <div id="dashboardProductsCreateContainer" class="dashboard-page-containers">
         <h3>Products Images</h3>
          <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to create product images.</h4>
            <form id="dashboardProductsCreate" class="form" v-if="noProduct">

                    <label class="col-12 mx-auto">
                      <div class="inputContainer">
                        <p>
                          <select class="form-control formInput" name="state" v-on:change="getImagesCount()" v-model="product_id">
                          <option value="" disabled>Choose Product</option>
                          <option :value="product.id" v-for="(product, index) in products">{{ product.title }}</option>
                       </select>
                      <span class="labelStyle">Products</span>
                     </p>
                     </div>
                   </label>

                   <h5 class="text-secondary text-center pb-3" v-if="imagesCount != null">You have {{ imagesCount }} images saved out of a 4 image limit. <br/> The images can be deleted in product edit.</h5>

                   <div class="input-container-file" v-for="(dataField, index) in dataFields">
                      <div class="preview">
                        <img v-if="dataField.url" :src="dataField.url" />
                      </div>
                       <p>
                         <input class="inputfile" :id="'file' + index" :ref="'file' + index"  type="file" :disabled="dataField.disabled == 1" v-on:change="selectImageField(index)" />
                         <label :for="'file' + index">
                           <i class="fa fa-upload file-icon"></i>
                          {{ index + 1 }}. Featured Image Upload
                         </label>
                         <i class="fa fa-check text-success check-icon" v-if="dataField.imageChosen"></i>
                       </p>
                   </div>
                   <!-- Error/Success messages from api -->
                   <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                   <p class="success-msg" v-if="success != null">{{ success }}</p>

                    <div class="dashboard-page-btn" v-on:click="saveImages()">
                      <input type="button"   value="Save Product Images" v-if="!loading"/>
                      <load-dots
                       v-if="loading"
                       v-bind:loading-dots="loadingDots"
                       >
                     </load-dots>
                   </div>
             </form>
        </div>
</template>



<script>
import LoadDots from '../pages/loaddots.vue';
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        noProduct: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        dataFields: [
          {
            imageChosen: false,
            url: null,
            disabled: 0,
            image_files: [],
          }
        ],
        imagesCount: null,
        isActive: false,
        products: [],
        product_id: null,
        currentIndex: '',
      }

  },
  mounted(){

    // retrieve categories for products on page load
     this.getProducts();
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();

  },
  watch: {
    noProduct: function(newNoProduct, oldNoProduct){
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
    anchorPage: function(id){
      anchorsPage(id);
    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    clearErrors: function(){
      this.errors = null;
      this.success = null;

    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsCreateContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    selectImageField: function(i){
      if(i == 0 && this.currentIndex == 0){
         // push array to the current uploaded image
          this.dataFields.push(
            {
              imageChosen: true,
              url: URL.createObjectURL(this.$refs['file' + i][0].files[0]),
              disabled: 1,
              image_files: this.$refs['file' + i][0].files[0],
            }
          );
          // push array to new empty array with all options of image upload for client
            this.dataFields.push(
              {
                imageChosen: false,
                url: null,
                disabled: 0,
                image_files: [],
              }
            );

            this.currentIndex++;
            // remove first empty array from dataFields
            this.dataFields.shift();
            // if imageRequired returns false run if condition
            if(!this.imageRequired(i)){
               this.dataFields.splice(i, 1);
               this.currentIndex = 0;
            }


      }

    else if(i > 0 && this.currentIndex <= 4){
        // remove from array last empty array that shows form File upload field
         this.dataFields.splice(i, 1);
         // push array to the current uploaded image
          this.dataFields.push(
            {
              imageChosen: true,
              url: URL.createObjectURL(this.$refs['file' + i][0].files[0]),
              disabled: 1,
              image_files: this.$refs['file' + i][0].files[0],
            }
          );
          // push array to new empty array with all options of image upload for client
          this.dataFields.push(
            {
              imageChosen: false,
              url: null,
              disabled: 0,
              image_files: [],
            }
          );
          // null messages with new image upload
          this.success = null;
          this.errors = null;
          this.currentIndex++;
          // if imageRequired returns false run if condition
          if(!this.imageRequired(i)){
             this.dataFields.splice(i, 1);
             this.currentIndex--;
          }
          this.dataInserted = true;
          this.templateHeight();
          // if currentIndex (image count) is gtr than number splice extra empty array and show msg.
          if(this.currentIndex > 3){
             this.dataFields.splice(i + 1, 1);
             this.errors = '4 images are the limit for a product images';
         }
      }

      else{
        // remove the last showing image upload button
        this.dataFields.splice(i, 1);
        this.errors =  '4 images are the limit for a product images';
      }


    },
    imageRequired: function(i){
      // if file type isn't an image clear image
      let file = this.$refs['file' + i][0].files[0];
      if(file['type'].split('/')[0] === 'image'){
          return true;
      }else{

          this.clearImage(i);
          this.errors = 'File must be an image to upload.';
          return false;
      }
    },
    clearImage: function(i){
      this.dataFields[i].imageChosen = false;
      this.$refs['file' + i][0].value = '';
      this.dataFields[i].url = null;
      this.dataFields[i].disabled = 0;

    },

    clearImages: function(){
      this.imagesCount = null;
      this.currentIndex = 0;
      // loop through first round of dataFields
      for(let i = 0; i <= this.dataFields.length; i++){
            this.dataFields.splice(i, 1);
            this.$refs['file' + i][0].value = '';
        }
        // loop through remaining dataFields that weren't picked up
        for(let m = 0; m <= this.dataFields.length; m++){
              this.dataFields.splice(m - 1, 1);
          }
          // add an empty array to dataFields for next selection
         this.dataFields.push(
           {
             imageChosen: false,
             url: null,
             disabled: 0,
             image_files: [],
           }
         );

    },
    validation: function(){
      if(this.product_id == null){
            this.errors = 'A Product must be chosen for product images.';
            return false;
      }else {
          return true;
      }

    },
      saveImages: function(){
        if(!this.validation()){
            return false;
          }
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let imageFileCount = 0;
          let fd = new FormData();
            for(let i = 0; i < self.dataFields.length; i++){
                if(self.dataFields[i].image_files != ''){
                  imageFileCount++;
                  fd.append('file' + imageFileCount, self.dataFields[i].image_files);
                }
              }
              fd.append('product_id', self.product_id)
              fd.append('imageFileCount', imageFileCount);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/productimagestore',fd,
            {
             headers: {
                      'Content-Type': 'multipart/form-data',
                      }
             })
             .then((res) => {

                if(res.data.success){
                    this.errors = null;
                    self.isActive = false;
                    self.loading = false;
                    self.product_id = null;
                    let savedImages = res.data.imagecount + res.data.imagessaving;
                    self.success = 'Product image was saved successfully! You have ' + savedImages + ' saved images.';
                    setTimeout(function(){

                      self.anchorPage('dashboardProductsCreateContainer');
                      self.clearImages();
                    }, 2000);
                }else{
                   self.product_id = null;
                   self.success = null;
                   self.isActive = false;
                   self.loading = false;
                   self.errors = 'There is a limit of 4 images. You have ' + res.data.imagecount + ' saved and trying to save ' + res.data.imagessaving;
                   setTimeout(function(){
                     self.anchorPage('dashboardProductsCreateContainer');
                     self.clearImages();

                   }, 2000);
                 }
            }).catch((error) => {
              console.log(error);
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Images Save', self);
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
               errorHandle(error, 'Product Get in Images', self);
              })
          },
          getImagesCount: function(){
              // for scope of this within axios
              let self = this;
                this.clearErrors();
                console.log(this.product_id);
                axios.post('/getimagescount',
                {
                  Authorization:  this.$store.state.admin.currentAdmin.token,
                  product_id: this.product_id
                },
                  )
                 .then((res) => {
                   if(res.data.images){
                      self.imagesCount = res.data.images;
                    }else{
                      self.imagesCount = 0;
                    }
                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Product Get Images Count', self);
                })
            },
    }


}


</script>
<style>
.preview img {
  width: 200px;
  height: 125px;
  margin: 0 0 20px 33px;
}

</style>
