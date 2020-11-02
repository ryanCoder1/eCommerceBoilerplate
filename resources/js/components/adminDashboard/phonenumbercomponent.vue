<template>
  <div class="">
    <form class="form py-5 pr-3">
        <div class="listings ml-3">
          <p class="listing-head">Listed Phone Number</p>
          <p class="listing-info" v-if="phoneNumber.info != null">{{ phoneNumber.info }}</p>
          <p class="listing-info" v-else>One not available</p>
        </div>
        <div class="inputContainer">
          <p>
          <input class="form-control"
            type="text"
            v-bind:class="{formInput: isActive}"
            v-model="number"
            v-on:keyup="changeShowSave($event)">

            <span class="labelStyle" >Phone Number (e.g. 234-555-5555)</span>
          </p>
        </div>
        <!-- Error/Success messages from api -->
        <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
        <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>

          <div class="dashboard-page-btn mx-auto" v-if="showSave" v-on:click="savePhoneNumber()">
            <input type="button" value="Save Phone Number" v-if="!loading && !updatedVis"/>
            <load-dots
             v-if="loading"
             v-bind:loading-dots="loadingDots"
             >
            </load-dots>
             <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
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
    data(){
      return{
          phoneNumber: '',
          number: null,
          isActive: false,
          loading: false,
          loadingDots: true,
          updatedVis: false,
          showSave: false,
          errors: null,
          success: null,
      }
    },
    mounted() {
      this.getPhoneNumber();
    },
    methods: {
      changeShowSave: function(event){
        this.errors = null;
        this.success = null;
        this.phoneNumberValidate(event);
      },
      phoneNumberValidate: function(event){
        // phone number validation.
        // if backspace keycode then run if block
        if(event.keyCode == 8 && this.number.length < 12){
           this.number.slice(-1, 1);
           this.showSave = false;
        }
        // if number string length add a '-'
        else if(this.number.length == 3){
          this.number = this.number + '-';
        }
        else if(this.number.length == 7){
          this.number = this.number + '-';
        }
        // if number length == 12 show save button
        else if(this.number.length == 12){
          this.showSave = true;
          this.errors = null;
        }
        // if number length Gtr than 12. show error and remove save button
        else if(this.number.length > 12){
          this.errors = 'Phone number has to be valid.';
          this.showSave = false;
        }
        else if(this.number.length < 13){
          this.errors = null;
          this.showSave = false;
        }
        else if(this.number.length < 12){
          this.showSave = false;
        }
      },
      getPhoneNumber: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/phonenumbershow',
            {
              Authorization:  this.$store.state.admin.currentAdmin.token
            },
              )
             .then((res) => {
               console.log(res.data);
               if(res.data.phonenumber != undefined && res.data.phonenumber.length ){
                   self.phoneNumber = res.data.phonenumber[0];
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Phone Number', self);
            })
        },
        savePhoneNumber: function(){
            // for scope of this within axios
            let self = this;
            self.loading = true;
              axios.post('/phonenumberstore',
              {
                info: self.number,
                Authorization:  self.$store.state.admin.currentAdmin.token
              },
                )
               .then((res) => {
                 if(res.data.success){
                   self.loading = false;
                   self.number = null;
                   self.success = 'Phone number saved successfully!';
                   self.getPhoneNumber();
                 }

              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Save Phone Number', self);
              })
          },

    }
  }
</script>

<style lang="css">
</style>
