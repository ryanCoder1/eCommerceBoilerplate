<template>
  <div class="non-popup-form-wrapper">

        <form  id="sendEmailForm" class="form" autocomplete="off">
          <h3>Verify email to reset password
            <span class="crossingLine"></span>
          </h3>
          <br/>
          <!-- Messages from back end after response -->
          <!-- emailSuccess is found email in DB and message sent to email -->
          <p class="bg-success text-light my-1 mx-2" v-if="emailSuccess">{{ emailSuccess }}</p>
          <!-- emailErrors if there is failure with smtp email process on server -->
          <p class="bg-danger text-light my-1" v-if="emailErrors">{{ emailErrors }}</p>
          <!-- errorMsg if no matched email found in DB -->
          <p class="bg-danger text-light my-1" v-if="errorMsg">{{ errorMsg }}</p>
          <router-link v-if="errorMsg" :to="{ path: '/register'}"><a>Register?</a></router-link>
            <label class="col-xs-12 col-sm-12">

              <div class="inputContainer">

                  <input id="createEmailInput1" class="form-control"  v-bind:class="{formInput: isActive}" type="email" v-model="form.email"/>
                  <span class="labelStyle">Email</span>

              </div>
            </label>
            <div class="col-xs-12 col-sm-12  mx-auto">
              <input type="button" class="submitBtn" value="Send Email" v-on:click="sendEmail"/>
            </div>


         </form>
  </div>
</template>



<script>
import { login } from '../../helper/auth';

export default {
    name: 'Login',
    data(){
      return{
        form: {
          email: '',
        },
        emailSuccess: '',
        emailErrors: '',
        errorMsg: '',
        isActive: true,
      }
    },
    methods: {
       sendEmail(){
          var self = this;
        axios.post('verifyEmailPassword', this.$data.form )
        .then((res) => {
          console.log(res);
          self.emailSuccess = '';
          self.emailErrors = '';
          self.errorMsg = '';
          if(undefined !== res.data.emailSuccess && res.data.emailSuccess.length){
            self.emailSuccess = res.data.emailSuccess;
          }
          else if(undefined !== res.data.emailErrors && res.data.emailErrors.length){
            self.emailErrors = res.data.emailErrors;
          }
          else if(undefined !== res.data.errorMsg && res.data.errorMsg.length){
            self.errorMsg = res.data.errorMsg;
          }
        }).catch((error) => {
          console.log(error);
        })
        }

     },
     computed: {

     }
  }

</script>
<style>


</style>
