<template>
  <transition  name="fade" appear>
    <div class="product-edit-image-container product-edit-child-container">
         <h5 class="product-edit-image-head">Main Image <span class="image-x" v-on:click="closeComponent()">X</span></h5>
                <ul class="product-edit-image">
                  <li>
                    <div v-if="noImage" class="preview-no-image">
                      <p>No current image</p>
                    </div>
                   <div v-if="!noImage" class="preview">
                     <!-- main product image -->
                       <img v-if="url == null && urlParent == null" :src="product.image_name != '' ? '../storage/images/' + product.image_path + '/' + product.image_name : ''"/></img>
                       <img v-if="url || urlParent" :src="url ? url : urlParent" />
                   </div>
                   <div class="input-container-file mt-2">
                     <p>
                       <input class="inputfile" id="file1" ref="file"  type="file" :disabled="disabled == 1" v-on:change="handleFile()" />
                       <label for="file1">
                         <i class="fa fa-upload file-icon"></i>
                         Main Image Upload
                       </label>
                       <i class="fa fa-check text-success check-icon" v-if="imageChosen"></i>
                     </p>
                   </div>


                     <!-- Error/Success messages from api -->
                     <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
                     <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>

                    <div class="dashboard-edit-btn" v-on:click="saveImage()">
                      <input type="button"   value="Save Image" v-if="!loading"/>
                      <load-dots
                       v-if="loading"
                       v-bind:loading-dots="loadingDots"
                       >
                     </load-dots>
                   </div>
                 </li>
               </ul>
         </div>
      </transition>
</template>



<script>
import LoadDots from '../pages/loaddots.vue';
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'load-dots': LoadDots,
    },
    props: {
      'product': Object,
      'urlParent': String,
    },
    data(){
      return{
        noProduct: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        imageChosen: false,
        imagesCount: null,
        isActive: false,
        products: [],
        product_id: null,
        image_file: '',
        url: null,
        disabled: 0,
        noImage: false,
      }

  },
  mounted(){
    if(this.product.image_name == ''){
        this.noImage = true;
    }
  },
  methods: {
    anchorPage: function(id){
      anchorsPage(id);
    },
    clearErrors: function(){
      this.errors = null;
      // look up products images in api
      this.getImagesCount();

    },
    handleFile: function(){
        this.errors = null;
        this.noImage = false;
        this.imageChosen = true;
        this.image_file = this.$refs.file.files[0];
        this.url = URL.createObjectURL(this.$refs.file.files[0]);
        this.disabled = 1;

    },
    sendUrlToParent: function(){
        this.$root.$emit('imageUrl', this.url);
    },

    clearImages: function(){
      this.product_id = null;
      this.image_file = '';
      this.url = null;
      this.disabled = 0;
      this.imageChosen = false;
      this.$refs.file.value = '';
      this.imagesCount = null;

    },
      saveImage: function(){

          // for scope of this within axios
          let self = this;
          this.loading = true;
          let fd = new FormData();
              fd.append('file', self.image_file)
              fd.append('product_id', self.product.id)
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/productmainimageedit',fd,
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
                    self.sendUrlToParent();
                    self.closeComponent();
                }
            }).catch((error) => {

              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Edit Main Image', self);
            })

        },
        closeComponent: function(){
          this.$root.$emit('closeMainImage', false);
        }


    }


}


</script>
<style scoped>
.product-edit-image-container {
  text-align: center;
  position: absolute;
  top: -15px;
  left: 20%;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 20px 1px rgba(0,0,0, .4);
}
.product-edit-image-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.image-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.preview-no-image {
  text-align: center;
  height: 200px;
  padding: 100px 0 0 0;
}
.product-edit-image {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-image li {
  width: 300px;
  padding: 0;
  margin: 0 10px 10px 10px;
}
.product-edit-image img {
  width: 100%;
  height: 250px;
  margin: 0;
  padding: 0;
  background-color: rgb(255, 255, 255);

}
.index-text-images {
  height: 200px;
  padding-top: 150px;

 }
.product-edit-image-none {
  width: 280px;
}
/* TRANSITION INTO COMPONENT */
.fade-enter-active{
     transition: opacity .5s;
 }
 .fade-leave-active {
     opacity: 0;
 }
 .fade-enter, .fade-leave-to {
     opacity: 0;
 }

</style>
