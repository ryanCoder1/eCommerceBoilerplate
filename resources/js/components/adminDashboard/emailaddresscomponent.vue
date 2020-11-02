<template>
  <div class="">
    <form class="form py-5 pr-3">
      <div class="listings ml-3">
        <p class="listing-head">Listed Email Address</p>
        <p class="listing-info" v-if="emailAddress.info != null">{{ emailAddress.info }}</p>
        <p class="listing-info" v-else>One not available</p>
      </div>
        <div class="inputContainer">
          <p>
          <input class="form-control"
            type="text"
            v-bind:class="{formInput: isActive}"
            v-model="email"
            v-on:keyup="clearErrors()">
            <span class="labelStyle" >Email Address</span>
          </p>
        </div>
        <!-- Error/Success messages from api -->
        <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
        <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>

          <div class="dashboard-page-btn mx-auto" v-if="showSave" v-on:click="saveEmailAddress()">
            <input type="button" value="Save Email Address" v-if="!loading && !updatedVis"/>
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
          emailAddress: [],
          email: null,
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
      this.getEmailAddress();
    },
    methods: {
      clearErrors: function(){
        this.showSave = true;
        this.errors = null;
        this.success = null;
      },
      validateEmail: function(){
        if(!this.validEmail()){
              this.errors = 'Email must be a valid format';
              return false;
            }
        else{
              return true;
             }

      },
      validEmail: function () {
          var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(this.email);
      },

      getEmailAddress: function(){
          // for scope of this within axios
          let self = this;

            axios.post('/emailaddressshow',
            {
              Authorization:  this.$store.state.admin.currentAdmin.token
            },
              )
             .then((res) => {
               console.log(res.data);
               if(res.data.emailaddress != undefined && res.data.emailaddress.length ){
                   self.emailAddress = res.data.emailaddress[0];
                }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Get Email Address', self);
            })
        },
        saveEmailAddress: function(){

           // validate email
           if(!this.validateEmail()){
             return false;
           }
            // for scope of this within axios
            let self = this;
            self.loading = true;
              axios.post('/emailaddressstore',
              {
                info: self.email,
                Authorization:  self.$store.state.admin.currentAdmin.token
              },
                )
               .then((res) => {
                 if(res.data.success){
                   self.loading = false;
                   self.email = null;
                   self.success = 'Email Address saved successfully!';
                   self.getEmailAddress();
                 }

              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Save Email Address', self);
              })
          },

    }
  }
</script>

<style lang="css">
</style>
