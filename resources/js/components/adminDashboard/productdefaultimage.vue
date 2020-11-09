<template>
  <div id="dashboardProductsEditContainer" class="dashboard-page-containers">
     <h3>Product default Image</h3>
      <h4 class="text-secondary">A default image to show incase a main image isn't current. </h4>
      <form id="dashboardProducts" class="form pb-2 pt-5">

                   <div class="input-container-file">
                     <p>
                       <input class="inputfile" id="file1" ref="file"  type="file" :disabled="disabled == 1" v-on:change="handleFile()" />
                       <label for="file1">
                         <i class="fa fa-upload file-icon"></i>
                         Default Image Upload
                       </label>
                       <i class="fa fa-check text-success check-icon" v-if="imageChosen"></i>
                     </p>
                   </div>
                   <div class="preview">
                     <img v-if="url" :src="url" />
                   </div>

                   <!-- Error/Success messages from api -->
                   <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                   <p class="success-msg" v-if="success != null">{{ success }}</p>

                    <div class="dashboard-page-btn" v-on:click="saveImage()">
                      <input type="button"   value="Save Default Image" v-if="!loading"/>
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
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        imageChosen: false,
        isActive: false,
        image_file: '',
        url: null,
        disabled: 0,
      }

  },
  mounted(){
    this.pageOpen();
  },
  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    anchorPage: function(id){
      anchorsPage(id);
    },
    clearErrors: function(){
      this.errors = null;
      // look up products images in api
      this.getImagesCount();

    },
    imageRequired: function(){
      // if file type isn't an image clear image
      let file =  this.$refs.file.files[0];
      if(file['type'].split('/')[0] === 'image'){
          return true;
      }else{

          this.clearImages();
          this.errors = 'File must be an image to upload.';
          return false;
      }
    },
    handleFile: function(){
       // check if image is authentic
       // if return false end method process
       if(!this.imageRequired()){
         return false
       }
        this.errors = null;
        this.imageChosen = true;
        this.image_file = this.$refs.file.files[0];
        this.url = URL.createObjectURL(this.$refs.file.files[0]);
        this.disabled = 1;

    },

    clearImages: function(){
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
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/productdefaultimage',fd,
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
                    self.success = 'Product default image was saved successfully!';
                    setTimeout(function(){

                      self.anchorPage('dashboardProductsCreateContainer');
                      self.clearImages();
                    }, 2000);
                }
            }).catch((error) => {

              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Default Image', self);
            })

        },

    }
}


</script>
<style scoped>


</style>
