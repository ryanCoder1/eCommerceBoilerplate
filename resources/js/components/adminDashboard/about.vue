<template>
  <div id="dashboardAboutContainer" class="dashboard-page-containers">
     <h3>About</h3>
      <h4 class="text-secondary" >About info that shows up on website.</h4>
      <!-- component is a checkbox with save button -->
      <check-component
      v-bind:title="about"
      v-bind:route-name="aboutLC">
      </check-component>

      <form id="dashboardAboutCreate" class="form py-5 pr-3">

                <div class="inputContainer">
                  <p>
                  <textarea class="form-control"
                    v-bind:class="{formInput: isActive}"
                    ref="caption"
                    rows="5"
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

                <!-- Error/Success messages from api -->
                <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                <p class="success-msg" v-if="success != null">{{ success }}</p>


              <div class="dashboard-page-btn" v-if="showSave" v-on:click="saveAbout()">
                <input type="button"   value="Save About" v-if="!loading"/>
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
import CheckComponent from './checkcomponent.vue';

  export default {
    components: {
      'check-component': CheckComponent,
      'load-dots': LoadDots,
    },
    data(){
      return{
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        showSave: false,
        isActive: false,
        captionVis: false,
        captionPicked: null,
        caption: '',
        captionLength: 0,
        errorsCaption: null,
        captionCharCount: 400,
        about: 'About',
        aboutLC: 'about',
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
      this.getAbout();
    },
  methods: {
    captionCount: function(index){

        // count the characters in this.caption on caption textarea
        this.captionLength = this.caption.length;
        // if caption length gtr 55 hide image upload until user meets below 56 character limit
        if(this.captionLength > this.captionCharCount){
          this.errorsCaption = 'Can\'t save with caption above ' + this.captionCharCount + ' characters!';
          this.showSave = false;
        }else{
          this.errorsCaption = null;
          this.showSave = true;
        }
      },
    showSaveBtn: function(){
        this.showSave = true;
      },
    anchorPage: function(id){
        anchorsPage(id);
      },
    pageOpen: function(){
        this.$root.$emit('subMenu', 'Personal Info');
      },
    templateHeight: function(){
        var elem = document.getElementById('dashboardAboutContainer').offsetHeight;
        this.sideBarHeight(elem);
      },
    sideBarHeight: function(elem){
        this.$root.$emit('sideBarHeight', elem);
      },
    clearCaption: function(){
        this.caption = '';
      },
    getAbout: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/aboutshow',
            {
              Authorization:  this.$store.state.admin.currentAdmin.token
            },
              )
             .then((res) => {
               if(res.data.about != undefined && res.data.about.length ){
                 console.log(res.data);
                  if(res.data.about[0].info != null){
                   self.caption = res.data.about[0].info;
                   self.captionLength = res.data.about[0].info.length;
                 }
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get About Caption', self);
            })
        },
    saveAbout: function(){

          // for scope of this within axios
          let self = this;
          this.loading = true;

            axios.post('/aboutstore',
             {
                info: self.caption,
                Authorization:  self.$store.state.admin.currentAdmin.token
              }
              )
             .then((res) => {

                if(res.data.success){
                    this.errors = null;
                    self.isActive = false;
                    self.loading = false;
                    self.success = 'The about has saved successfully!';
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'About Save', self);
            })

        },

    }
  }
</script>

<style lang="css">

</style>
