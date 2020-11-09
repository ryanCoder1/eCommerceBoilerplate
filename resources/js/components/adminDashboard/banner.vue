<template>
  <div id="dashboardBannerContainer" class="dashboard-page-containers">
     <h3>Banner</h3>
      <h4 class="text-secondary" >Banner that shows up on website.</h4>
      <!-- component is a checkbox with save button -->
      <check-component
      v-bind:title="banner"
      v-bind:route-name="bannerLC">
      </check-component>

      <h4 class="text-secondary banner-link" v-on:click="showImages()" >
        <span v-if="!bannerVis">Current Banners +</span>
        <span v-if="bannerVis">Close Banners -</span>
       </h4>
       <h4 class="text-secondary" v-if="bannerVis">
         <!-- holds the already listed banners to choose from -->
         <banner-choose
         v-if="bannerVis">
         </banner-choose>
        </h4>
      <form id="dashboardBannerCreate" class="form py-5 pr-3">

             <div class="input-container-file">
                <div class="banner-preview">
                  <img v-if="dataField[0] !== undefined && dataField[0].url !== null" :src="dataField[0].url" />
                </div>

                <div class="inputContainer">
                  <p>
                  <input class="form-control"
                    type="text"
                    v-bind:class="{formInput: isActive}"
                    v-model="title"
                    v-on:keyup="titleCount()"
                    >
                    </input>
                    <span class="labelStyle" >Title</span>
                    <span >
                      There are
                      <span class="text-success" v-if="titleLength >= 0 && titleLength <= titleCharCount">{{ titleLength }} characters with a limit of {{ titleCharCount}}.</span>
                      <span class="text-danger" v-if="titleLength > titleCharCount">{{ titleLength }} must be {{ titleCharCount }} characters or below!</span>
                    </span>
                  </p>
                </div>
                <div class="inputContainer">
                  <p>

                  <textarea class="form-control"
                    v-bind:class="{formInput: isActive}"
                    ref="caption"
                    v-model="caption"
                    v-on:keyup="captionCount()"
                    >
                    </textarea>
                    <span class="labelStyle" >Caption</span>
                    <span >
                      There are
                      <span class="text-success" v-if="captionLength >= 0 && captionLength <= captionCharCount">{{ captionLength }} characters with a limit of {{ captionCharCount }}.</span>
                      <span class="text-danger" v-if="captionLength > captionCharCount">{{ captionLength }} must be {{ captionCharCount }} characters or below!</span>

                    </span>
                  </p>
                </div>
                 <p>
                   <input
                   class="inputfile"
                   id="file"
                   ref="file"
                   type="file"
                   :disabled="dataField.disabled == 1"
                   v-on:change="selectImageField()"
                   />
                   <label for="file">
                     <i class="fa fa-upload file-icon"></i>
                     Banner Image Upload
                   </label>
                   <i class="fa fa-check text-success check-icon" v-if="dataField.chosen == true"></i>
                 </p>
             </div>
             <!-- Error/Success messages from api -->
             <p class="error-msg" v-if="errors != null">{{ errors }}</p>
             <p class="success-msg" v-if="success != null">{{ success }}</p>


              <div class="dashboard-page-btn" v-if="showSave && showSaveImg" v-on:click="saveBanner()">
                <input type="button"   value="Save Banner" v-if="!loading"/>
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
import BannerChoose from './bannerchoose.vue';
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';
import CheckComponent from './checkcomponent.vue';

  export default {
    components: {
      'check-component': CheckComponent,
      'banner-choose': BannerChoose,
      'load-dots': LoadDots,
    },
    data(){
      return{
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        showSave: false,
        showSaveImg: false,
        dataField: [
          {
            chosen: false,
            url: null,
            title: null,
            caption: null,
            disabled: 0,
            image_files: [],
          },
        ],
        imagesCount: null,
        isActive: false,
        currentIndex: '',
        checked: false,
        updated: 'Updated!',
        updatedVis: false,
        captionVis: false,
        captionPicked: null,
        imageVis: false,
        caption: '',
        title: null,
        captionLength: 0,
        errorsCaption: null,
        captionCharCount: 125,
        titleLength: 0,
        errorsTitle: null,
        titleCharCount: 40,
        banner: 'Banner',
        bannerLC: 'banner',
        bannerVis: false,
      }
    },
    created(){
      this.$root.$on('adjustSideBarHeight', (value) => {
        this.templateHeight();
      })
    },
    mounted(){
      let self = this;
      setTimeout(function(){
        self.templateHeight();
      },500);
      // call method to alert sidemenu that the current page is in a sub menu.
      this.pageOpen();
    },
    methods: {
      captionCount: function(index){
        this.clearMsgs();
        // count the characters in this.caption on caption textarea
        this.captionLength = this.caption.length;
        // if caption length gtr 55 hide image upload until user meets below 56 character limit
        if(this.captionLength > this.captionCharCount){
          this.errorsCaption = 'Image won\'t load with caption above ' + this.captionCharCount + ' characters!';
          this.showSave = false;
        }else{
          this.errorsCaption = null;
          if(this.titleLength <= this.titleCharCount){
            this.showSave = true;
          }
        }
      },
      titleCount: function(index){
        this.clearMsgs();
        // count the characters in this.caption on caption textarea
        this.titleLength = this.title.length;
        // if caption length gtr 55 hide image upload until user meets below 56 character limit
        if(this.titleLength > this.titleCharCount){
          this.errorsTitle = 'Image won\'t load with title above ' + this.titleCharCount + ' characters!';
          this.showSave = false;
        }else{
          this.errorsTitle = null;
          if(this.captionLength <= this.captionCharCount){
            this.showSave = true;
          }
        }
      },
      showSaveBtn: function(){
        this.showSave = true;
        this.checked = !this.checked;
      },
      captionSelect: function(item){

        if(!item){
          this.imageVis = true;
        }else{
          this.captionVis = true;
          this.imageVis = true;
        }
      },
      showImages: function(){
        this.bannerVis = !this.bannerVis;
      },
      anchorPage: function(id){
        anchorsPage(id);
      },
      pageOpen: function(){
        this.$root.$emit('subMenu', 'Personal Info');
      },
      clearMsgs: function(){
        this.errors = null;
        this.success = null;


      },
      templateHeight: function(){
        var elem = document.getElementById('dashboardBannerContainer').offsetHeight;
        this.sideBarHeight(elem);
      },
      sideBarHeight: function(elem){
        this.$root.$emit('sideBarHeight', elem);
      },
      selectImageField: function(){
        // null messages with new image upload
        this.success = null;
        this.errors = null;
        if(!this.imageRequired()){
          return false;
        }
           // push array to the current uploaded image
            this.dataField.push(
              {
                chosen: true,
                url: URL.createObjectURL(this.$refs['file'].files[0]),
                title: this.title,
                caption: this.caption,
                disabled: 1,
                image_files: this.$refs['file'].files[0],
              }
            );
            if(this.dataField.length > 1){
              // remove first empty array from dataFields
              this.dataField.shift();
          }
            // enable save button
          if(this.captionLength <= this.captionCharCount && this.titleLength <= this.titleCharCount){
            this.showSave = true;
          }
            this.showSaveImg = true;

      },
      imageRequired: function(i){
        // if file type isn't an image clear image
        console.log(this.$refs['file'].files);
        let file = this.$refs['file'].files[0];
        if(file.type.split('/')[0] === 'image'){
            return true;
        }else{

            this.clearImage();
            this.errors = 'File must be an image to upload.';
            return false;
        }
      },
      clearImage: function(){

        this.caption = '';
        this.title = '';
        this.dataField[0].chosen = false;
        this.$refs['file'].value = '';
        this.dataField[0].url = null;
        this.dataField[0].disabled = 0;
        this.dataField[0].title = null;
        this.$refs['caption'].value = null;
          this.dataField.splice(0,1);
      },
      saveBanner: function(){

          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();

              fd.append('file', self.dataField[0].image_files);
              fd.append('title', self.dataField[0].title);
              fd.append('caption', self.dataField[0].caption);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/bannerstore',fd,
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
                    self.success = 'Banner image have saved successfully!';
                    setTimeout(function(){
                      self.clearImage();
                      // self.anchorPage('dashboardSliderFeaturedContainer');

                    }, 2000);
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Featured Save', self);
            })

        },

    }
  }
</script>

<style lang="css">
#dashbaordBannerCreate {
  position: relative;

}
.banner-link {
  position: relative;
  cursor: pointer;
}
.banner-checkbox  {
  text-align: center;
  width: 100%;
}

.banner-checbox-input > label {
  margin: 0 auto;
}
.banner-checbox-span {
  width: 100%;
  display: block;
  text-align: center;
}
.banner-preview img{
  max-width: 320px;
  width: 100%;
  height: 100px;
  animation: bannerSlide .6s, ease-in-out, .0s, forwards;
  }
  @keyframes bannerSlide {
    0% {
      transform: translateY(-40px);
    }
  100% {
    transform: translateY(0px);
  }
  }
.banner-update-grow {
  font-size: 20px;
  transform: scale(1);
  animation: bannerGrow .3s, linear, .0s, forwards;
}
@keyframes bannerGrow {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
</style>
