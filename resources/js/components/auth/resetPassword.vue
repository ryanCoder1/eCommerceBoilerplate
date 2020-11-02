<template>
  <div class="non-popup-form-wrapper">
    <p class="text-secondary my-5 mx-auto" v-if="unAuthPage">{{ unAuthPage }}</p>
        <form  id="resetPasswordForm" class="form" autocomplete="off">
          <h3>Reset password
            <span class="crossingLine"></span>
          </h3>
          <br/>
          <!-- Messages from back end after response -->
          <p class="bg-success text-light my-1" v-if="resetSuccess">{{ resetSuccess }}</p>
          <p class="bg-danger text-light my-1" v-if="resetNonActive">{{ resetNonActive }}</p>
           <p v-if="resetNonActive"><router-link  :to="{ path: '/sendemail'}"><a>Forgot Password?</a></router-link></p>

          <p class="bg-danger text-light my-1" v-if="errorMsg">{{ errorMsg }}</p>
          <p class="bg-danger text-light my-1" v-if="notMatchPassword">{{ notMatchPassword }}</p>

            <label class="col-xs-12 col-sm-12">
              <div class="inputContainer">
                  <input class="form-control"  v-bind:class="{formInput: isActive}" type="password" v-model="password"/>
                  <span class="labelStyle">Password</span>
              </div>
            </label>
            <label class="col-xs-12 col-sm-12">
              <div class="inputContainer">
                  <input class="form-control"  v-bind:class="{formInput: isActive}" type="password" v-model="reenterPassword"/>
                  <span class="labelStyle">Re-enter Password</span>
              </div>
            </label>
            <div class="col-xs-12 col-sm-12  mx-auto">
              <input type="button" class="submitBtn" value="Reset password" v-on:click="updatePassword()"/>
            </div>


         </form>
  </div>
</template>



<script>

export default {
    name: 'Login',
    data(){
      return{
        password: '',
        reenterPassword: '',
        notMatchPassword: '',
        resetSuccess: '',
        resetNonActive: '',
        errorMsg: '',
        isActive: true,
        unAuthPage: '',
      }
    },
    created(){
    },
    methods: {
      updatePassword(){

           var self = this;
           var email = this.$route.params.email;
           var token = this.$route.params.token;
           if(this.password === this.reenterPassword && this.password.length >= 6){
              axios.post('/verifyEmailResetPassword', {email: email, verifyToken: token, password: this.password},
              {
              headers: {
                  Authorization: 'Bearer ' + self.token,
                  'X-CSRF-TOKEN': myToken.csrfToken,

                }
              })
               .then((res) => {
                 self.resetSuccess = '';
                 self.resetNonActive = '';
                 self.notMatchPassword = '';
                 if( undefined !== res.data.verifyResetSuccess && res.data.verifyResetSuccess.length){
                    self.resetSuccess = res.data.verifyResetSuccess;
                 }
                 else if(undefined !== res.data.verifyResetNonActive && res.data.verifyResetNonActive.length){
                    self.resetNonActive = res.data.verifyResetNonActive;
                 }

              }).catch((error) => {
                console.log(error);
            })
          }else{
            this.notMatchPassword = 'Passwords must match to reset and have a length of 6 characters or more.';
          }
        }
    },

  }

</script>
<style>


</style>
