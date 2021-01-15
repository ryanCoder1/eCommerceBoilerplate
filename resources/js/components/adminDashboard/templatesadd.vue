<template>
  <div id="dashboardTemplateAddContainer" class="dashboard-page-containers">
     <h3>Templates</h3>
      <h4 class="text-secondary">Template to add</h4>
      <form id="dashboardTemplateCreate" class="form">

               <img class="template-image ml-4 mb-2" v-if="url" :src="url" />
               <div class="input-container-file">
                 <p>
                   <input class="inputfile" id="templateFile" ref="file"  type="file" v-on:change="handleFile()" />
                   <label for="templateFile">
                     <i class="fa fa-upload file-icon"></i>
                     Template Image Upload
                   </label>
                   <i class="fa fa-check text-success check-icon" v-if="imageChosen"></i>
                 </p>
               </div>

               <label class="col-xs-12 col-sm-12">
               <div class="inputContainer">
                 <p>
                   <input
                   class="form-control"
                   v-bind:class="{formInput: isActive}"
                   type="text"
                   v-model="caption"
                   v-on:keyup="templateSave()"/>
                   <span class="labelStyle" >Template name</span>
                 </p>
               </div>
               </label>

               <!-- Error/Success messages from api -->
               <p class="error-msg" v-if="errors != null">{{ errors }}</p>
               <p class="success-msg" v-if="success != null">{{ success }}</p>


               <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveTemplate()">
                 <input type="button"   value="Save Template" v-if="!loading"/>
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
import { errorHandle } from '../../helper/errors';
  export default {
    components: {
      'load-dots': LoadDots,
    },
    data() {
      return{
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        imageChosen: false,
        isActive: false,
        image_file: '',
        url: null,
        showSave: false,
        showSaveImg: false,
        caption: '',
        captionLength: 0,
      }
    },
    mounted(){

       // call method to alert sidemenu that the current page is in a sub menu.
       this.pageOpen();

    },
    methods: {
      pageOpen: function(){
        this.$root.$emit('subMenu', 'Templates');
      },
      clearErrors: function(){
        this.errors = null;
      },
      templateHeight: function(){
        var elem = document.getElementById('dashboardTemplateAddContainer').offsetHeight;
        this.sideBarHeight(elem);
      },
      sideBarHeight: function(elem){
        this.$root.$emit('sideBarHeight', elem);
      },
      templateSave: function(){
        // count the characters in this.caption on caption textarea
        this.captionLength = this.caption.length;
        // if caption length gtr 55 hide image upload until user meets below 56 character limit
        if(this.captionLength > 0){
          if(this.image_file){
              this.showSave = true;
          }
        }else{
          this.showSave = false;
        }
      },
      showSaveBtn: function(){
        this.showSave = true;
      },
      hideSaveBtn: function(){
        this.showSave = false;
      },
      handleFile: function(){
        // check if image is valid
        if(this.imageRequired()){

            this.image_file = this.$refs.file.files[0];
            this.url = URL.createObjectURL(this.$refs.file.files[0]);
            if(this.captionLength > 0){
              this.showSave = true;
            }
            let self = this;
            setTimeout(function(){
                self.templateHeight();
            }, 100);
          }

      },
      imageRequired: function(i){
        // if file type isn't an image clear image
        let file = this.$refs.file.files[0];
          if(file['type'].split('/')[0] === 'image'){
              return true;
          }else{
              this.errors = 'File must be an image to upload.';
              return false;
          }
      },
      clearFields: function(){
        this.caption = '';
        this.$refs.file.value = '';
        this.url = null;
      },
      saveTemplate: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
              fd.append('file',self.image_file)
              fd.append('template_name',self.caption)
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/templatestore',fd,
            {
             headers: {
                      'Content-Type': 'multipart/form-data',
                      }
             })
             .then((res) => {
               if(res.data.success){

                  self.clearFields();
                  self.loading = false;
                  self.success = 'Template was saved successfully!';
             }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Template Save', self);
            })

        },
   }
}
</script>

<style lang="css">
.template-image  {
  width: auto;
  height: 175px;
}
</style>
