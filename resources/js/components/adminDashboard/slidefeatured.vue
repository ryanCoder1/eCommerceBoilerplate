<template>
      <div id="dashboardSliderFeaturedContainer" class="dashboard-page-containers">
         <h3>Slider Featured</h3>
             <h5 class="text-secondary slider-featured-checkbox h5">
               <div class="slider-featured-checkbox-span">
                 Slider Featured
                 <span v-if="!checked"> is off </span>
                 <span v-if="checked"> is on </span>
                 <span v-if="showSave">if saved</span>
               </div>
               <div class="checkbox slider-featured-checbox-input">
                 <input type="checkbox" id="switch" :checked="checked" v-on:change="showSaveBtn()"/>
                 <label for="switch">Toggle</label>
               </div>
               <div class="dashboard-edit-btn slider-featured-checbox-span" v-if="showSave">
                 <div class="dashboard-page-btn" v-on:click="saveFeaturedUse()">
                   <input type="button"   value="Save Changes" v-if="!loading && !updatedVis"/>
                   <load-dots
                    v-if="loading"
                    v-bind:loading-dots="loadingDots"
                    >
                  </load-dots>
                  <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
                </div>
              </div>
             </h5>
          <h4 class="text-secondary slider-featured-link" v-on:click="showImages()" ><span >Current Slider Featured Images +</span>  </h4>
            <form id="dashboardSliderFeaturedCreate" class="form py-5 pr-3">

                <slide-featured-images
                  v-if="featuredImagesVis"
                  v-bind:features="features">
                </slide-featured-images>


                   <div class="input-container-file" v-for="(dataField, index) in dataFields">
                      <div class="preview">
                        <img v-if="dataField.url" :src="dataField.url" />
                      </div>

                      <div v-if="index < currentIndex ">
                        caption
                        <p>{{ dataField.caption }}</p>
                      </div>
                      <div class="inputContainer" v-if="currentIndex == index">
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
                      <div class="inputContainer" v-if="currentIndex == index">
                        <p>

                        <textarea class="form-control"
                          v-bind:class="{formInput: isActive}"
                          :ref="'caption' + index"
                          v-model="caption"
                          v-on:keyup="captionCount()"
                          >
                          </textarea>
                          <span class="labelStyle" >Caption</span>
                          <span >
                            There are
                            <span class="text-success" v-if="captionLength >= 0 && captionLength <= captionCharCount">{{ captionLength }} characters with a limit of 55.</span>
                            <span class="text-danger" v-if="captionLength > captionCharCount">{{ captionLength }} must be 55 characters or below!</span>

                          </span>
                        </p>
                      </div>
                       <p
                         class="bg-danger text-light p-2 ml-4 d-inline"
                         v-if="errorsCaption != null && currentIndex == index">
                         {{ errorsCaption }}
                       </p>
                       <p v-if="errorsCaption == null && currentIndex == index">
                         <input
                         class="inputfile"
                         :id="'file' + index"
                         :ref="'file' + index"
                         type="file"
                         :disabled="dataField.disabled == 1"
                         v-on:change="selectSliderField(index)"
                         />
                         <label :for="'file' + index">
                           <i class="fa fa-upload file-icon"></i>
                          {{ index + 1 }}. Featured Image Upload
                         </label>
                         <i class="fa fa-check text-success check-icon" v-if="dataField.imageChosen"></i>
                       </p>
                   </div>
                     <!-- Error/Success messages from api -->
                     <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
                     <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>

                    <div class="dashboard-page-btn" v-on:click="saveFeatured()">
                      <input type="button"   value="Save Slider Featured" v-if="!loading"/>
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
import SlideFeaturedImages from './slidefeaturedimages.vue';
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'load-dots': LoadDots,
      'slide-featured-images': SlideFeaturedImages,
    },
    data(){
      return{
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        showSave: false,
        features: [],
        featuredImagesVis: false,
        dataFields: [
          {
            imageChosen: false,
            url: null,
            disabled: 0,
            title: null,
            caption: null,
            image_files: [],
          },

        ],
        imagesCount: null,
        isActive: false,
        products: [],
        product_id: null,
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
        captionCharCount: 55,
        titleLength: 0,
        errorsTitle: null,
        titleCharCount: 25,
      }

  },
  created(){
    this.$root.$on('closeImages', (value) => {
      this.featuredImagesVis = false;
    })
  },
  mounted(){

     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();
     this.getFeatured();
     this.getFeaturedUse();
  },

  methods: {
    captionCount: function(index){
      // count the characters in this.caption on caption textarea
      this.captionLength = this.caption.length;
      // if caption length gtr 55 hide image upload until user meets below 56 character limit
      if(this.captionLength > this.captionCharCount){
        this.errorsCaption = 'Image won\'t load with caption above 55 characters!';
      }else{
        this.errorsCaption = null;
      }
    },
    titleCount: function(index){
      // count the characters in this.caption on caption textarea
      this.titleLength = this.title.length;
      // if caption length gtr 55 hide image upload until user meets below 56 character limit
      if(this.titleLength > this.titleCharCount){
        this.errorsTitle = 'Image won\'t load with title above 25 characters!';
      }else{
        this.errorsTitle = null;
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
      this.featuredImagesVis = !this.featuredImagesVis;
    },
    anchorPage: function(id){
      anchorsPage(id);
    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Slides');
    },
    clearErrors: function(){
      this.errors = null;
      this.success = null;
      // look up products images in api
      this.getImagesCount();

    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardSliderFeaturedContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    handleFile: function(i){
        this.errors = null;
        this.imageFields[i].imageChosen = true;
        this.imageFields[i].image_files = this.$refs['file' + i][0].files[0];
        this.imageFields[i].url = URL.createObjectURL(this.$refs['file' + i][0].files[0]);
        this.imageFields[i].disabled = 1;
        this.templateHeight();
        this.currentIndex = i;

    },
    selectSliderField: function(i){
      // null messages with new image upload
      this.success = null;
      this.errors = null;
      if(i == 0 && this.currentIndex == 0){
         // push array to the current uploaded image
          this.dataFields.push(
            {
              imageChosen: true,
              url: URL.createObjectURL(this.$refs['file' + i][0].files[0]),
              disabled: 1,
              title: this.title,
              caption: this.$refs['caption' + i][0].value,
              image_files: this.$refs['file' + i][0].files[0],
            }
          );
          // push array to new empty array with all options of image upload for client
            this.dataFields.push(
              {
                imageChosen: false,
                url: null,
                disabled: 0,
                title: null,
                caption: null,
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
              title: this.title,
              caption: this.$refs['caption' + i][0].value,
              image_files: this.$refs['file' + i][0].files[0],
            }
          );
          // push array to new empty array with all options of image upload for client
          this.dataFields.push(
            {
              imageChosen: false,
              url: null,
              disabled: 0,
              title: null,
              caption: null,
              image_files: [],
            }
          );

          this.currentIndex++;
          // if imageRequired returns false run if condition
          if(!this.imageRequired(i)){
             this.dataFields.splice(i, 1);
             this.currentIndex--;
          }
          this.dataInserted = true;
          this.templateHeight();

          if(this.currentIndex > 4){
             this.dataFields.splice(i + 1, 1);
             this.errors = '5 images are the limit for a slider featured';
         }
      }

      else{
        // remove the last showing image upload button
        this.dataFields.splice(i, 1);
        this.errors = '5 images are the limit for a slider featured';
      }
      this.captionVis = false;
      this.imageVis = false;
      this.captionPicked = null;
      this.captionLength = 55;
      this.caption = '';
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
      this.dataFields[i].title = null;
      this.$refs['caption' + i][0].value = null;

    },

    clearImages: function(){
      this.imagesCount = null;
      this.currentIndex = 0;
      // loop through first round of dataFields
      for(let i = 0; i <= this.dataFields.length; i++){
            this.dataFields.splice(i, 1);
        }
        console.log(this.dataFields);
        // loop through remaining dataFields that weren't picked up
        for(let m = 0; m <= this.dataFields.length; m++){
              this.dataFields.splice(m - 1, 1);
          }
          console.log(this.dataFields);
          // add an empty array to dataFields for next selection
         this.dataFields.push(
           {
             imageChosen: false,
             url: null,
             disabled: 0,
             title: null,
             caption: null,
             image_files: [],
           }
         );

    },
    removeUpdated: function(){
          let self = this;
          setTimeout(function(){
             self.updatedVis = false;
             self.showSave = false;
          }, 2000);


    },
      saveFeatured: function(){

          // for scope of this within axios
          let self = this;
          this.loading = true;

          let imageFileCount = 0;
          let fd = new FormData();

            for(let i = 0; i < self.dataFields.length; i++){
                if(self.dataFields[i].image_files != ''){
                  imageFileCount++;
                  fd.append('file' + imageFileCount, self.dataFields[i].image_files);
                  fd.append('title' + imageFileCount, self.dataFields[i].title);
                  fd.append('caption' + imageFileCount, self.dataFields[i].caption);
                }
              }
              fd.append('imageFileCount', imageFileCount);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/featuredstore',fd,
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
                    self.getFeatured();
                    self.success = 'Featured images have saved successfully!';
                    setTimeout(function(){
                      self.clearImages();
                      self.anchorPage('dashboardSliderFeaturedContainer');

                    }, 2000);
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Featured Save', self);
            })

        },
        getFeatured: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/featuredshow',
              {
                Authorization:  this.$store.state.admin.currentAdmin.token
              },
                )
               .then((res) => {
                 console.log(res);
                 if(res.data.featured != undefined && res.data.featured.length ){
                    self.features = res.data.featured;
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Get Featured', self);
              })
          },
          getFeaturedUse: function(){
              // for scope of this within axios
              let self = this;

                axios.post('/featureduse',
                {
                  Authorization:  this.$store.state.admin.currentAdmin.token
                },
                  )
                 .then((res) => {
                   console.log(res.data);
                   if(res.data.using != undefined && res.data.using.length ){
                       if(res.data.using[0].using == 0){
                         self.checked = false;
                       }else{
                         self.checked = true;
                       }
                    }
                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Get Featured Use', self);
                })
            },
            saveFeaturedUse: function(){
                // for scope of this within axios
                let self = this;

                  axios.post('/featureduseupdate',
                  {
                    using: self.checked,
                    Authorization:  self.$store.state.admin.currentAdmin.token
                  },
                    )
                   .then((res) => {
                     if(res.data.success){
                       self.loading = false;
                       self.updatedVis = true;
                       self.removeUpdated();
                     }

                  }).catch((error) => {
                    // error handling function with error, name axios is used for, and this.
                   errorHandle(error, 'Featured Use Save', self);
                  })
              },


    }


}


</script>
<style>
#dashbaordSliderFeaturedCreate {
  position: relative;

}
.slider-featured-link {
  cursor: pointer;
}
.slider-featured-checkbox  {
  text-align: center;
  width: 100%;
}

.slider-featured-checbox-input > label {
  margin: 0 auto;
}
.slider-featured-checbox-span {
  width: 100%;
  display: block;
  text-align: center;
}
.featured-updated-grow {
  font-size: 20px;
  transform: scale(1);
  animation: featuredGrow .3s, linear, .0s, forwards;
}
@keyframes featuredGrow {
  0% {
    transform: scale(0);
  }
  100% {
    transform: scale(1);
  }
}
</style>
