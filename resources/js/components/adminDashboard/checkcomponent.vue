<template>
        <div id="dashboardPersonalInfoContainer" class="">
              <h5 class="text-secondary checkbox h5">
                <div class="checkbox-span">
                  {{ title }}
                    <span v-if="!checked"> is off website</span>
                    <span v-if="checked"> is on website</span>
                    <span v-if="showSave">if saved</span>
                </div>
                <div class="checkbox checbox-input">
                  <input type="checkbox" :id="'switch' + routeName" :checked="checked" v-on:change="showSaveBtn()"/>
                  <label :for="'switch' + routeName">Toggle</label>
                </div>
                <div class="dashboard-edit-btn checbox-span" v-if="showSave">
                  <div class="dashboard-page-btn" v-on:click="saveUse()">
                    <!-- in :value/if condition to save and show or save and hide option -->
                    <input type="button" :value="checked ? ' Save and Show '+ title + ' on website' : 'Save and Hide '+ title + ' on website'" v-if="!loading && !updatedVis"/>
                    <load-dots
                     v-if="loading"
                     v-bind:loading-dots="loadingDots"
                     >
                    </load-dots>
                     <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
                 </div>
               </div>
              </h5>
        </div>
</template>

<script>

import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';

export default {
  components: {
    'load-dots': LoadDots,
  },
  props: {
    'title': String,
    'routeName': String,

  },
  data(){
    return{
      checked: false,
      showSave: false,
      loading: false,
      loadingDots: true,
      updatedVis: false,
      using: null,
      updated: 'Updated!',
    }
  },
  mounted() {
      this.getUse();
  },
  methods: {
    adjustSideBarHeight: function(elem){
      this.$root.$emit('adjustSideBarHeight', elem);
    },
    showSaveBtn: function(name){
      this.showSave = true;
      this.checked = !this.checked;
      this.adjustSideBarHeight();

    },
    removeUpdated: function(){
          let self = this;
          setTimeout(function(){
             self.updatedVis = false;
             self.showSave = false;
          }, 2000);
    },
    getUse: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/' + self.routeName + 'useshow',
          {
            Authorization:  this.$store.state.admin.currentAdmin.token
          },
            )
           .then((res) => {
             console.log(res.data.using);
             if(res.data.using != undefined && res.data.using.length ){
                 if(res.data.using[0].using == 0){
                   self.checked = false;
                 }else{
                   self.checked = true;
                 }
              }
          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Get ' + self.routeName + ' Use', self);
          })
      },
      saveUse: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;
            axios.post('/' + self.routeName + 'usestore',
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
             errorHandle(error, 'Save ' + self.routeName + ' Use', self);
            })
        },

  }
}
</script>

<style lang="css">
.checkbox  {
  text-align: center;
  width: 100%;
}

.checbox-input > label {
  margin: 0 auto;
}
.checbox-span {
  width: 100%;
  display: block;
  text-align: center;
}
</style>
