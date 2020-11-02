<template>
        <div id="dashboardSocialMediaContainer" class="dashboard-page-containers">
           <h3>Personal Info Social Media</h3>
            <h4 class="text-secondary" >Social Media shows up on website.</h4>
              <!-- component is a checkbox with save button -->
              <social-media
              v-bind:title="socialMedia"
              v-bind:route-name="socialMediaLC">
              </social-media>
              <!-- component to add links -->
              <social-media-input>
              </social-media-input>


          </div>
</template>

<script>

import SocialMedia from './checkcomponent.vue';
import SocialMediaInput from './socialmediacomponent.vue';
import { errorHandle } from '../../helper/errors';

  export default {
    components: {
      'social-media': SocialMedia,
      'social-media-input': SocialMediaInput,
    },
    data(){
      return{
        socialMedia: 'Social Media',
        socialMediaLC: 'socialmedia',

      }
    },
    created(){
      this.$root.$on('adjustSideBarHeight', (value) =>{
         this.templateHeight();
      })
    },
    mounted() {
      // call pageOpen for what subMenu the component is in
      this.pageOpen();
      // wait setTimeout to call templateHeight for sidebar height adjustment
      let self = this;
      setTimeout(function(){
        self.templateHeight();
      },500);
    },
    methods: {
      pageOpen: function(){
        this.$root.$emit('subMenu', 'Personal Info');
      },
      templateHeight: function(){
        var elem = document.getElementById('dashboardSocialMediaContainer').offsetHeight;
        this.sideBarHeight(elem);
      },
      sideBarHeight: function(elem){
        this.$root.$emit('sideBarHeight', elem);
      },


    }
  }
</script>

<style lang="css">
</style>
