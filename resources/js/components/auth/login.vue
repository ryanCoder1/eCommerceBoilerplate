<template>
   <div class="non-popup-form-wrapper">

         <form  id="loginForm" class="form" autocomplete="off">

                  <h3>LOG IN
                    <span class="crossingLine"></span>
                  </h3>

                  <br/>
                <!--  <hr id="topLine2"></hr> -->
                  <p class="bg-success text-light my-1" v-if="successEmail">{{ successEmail }}</p>
                  <p class="bg-danger text-light my-1" v-if="verifyEmailNonActive">{{ verifyEmailNonActive }}</p>
                  <p v-if="alertLogout"> {{ alertLogout }}</p>

                  <label class="col-xs-12 col-sm-12">
                    <div class="inputContainer">

                        <input id="createEmailInput1" class="form-control"  v-bind:class="{formInput: isActive}" type="email" name="email" v-model="form.email"/>
                        <span class="labelStyle">Email</span>

                    </div>
                  </label>

                  <label class="col-xs-12 col-sm-12">
                    <div class="inputContainer">

                        <input id="createPasswordInput1" class="form-control" v-bind:class="{formInput: isActive}"   type="password" name="password"  v-model="form.password"/>
                        <span class="labelStyle">Password</span>

                    </div>
                  </label>
                     <br/>
                  <!-- <p class="errorMsg" v-if="errors.email">{{ errors.email }} </p> -->

                  <div class="col-xs-12 col-sm-12  mx-auto">
                    <input type="button" class="submitBtn" name="signin" value="Log In" v-on:click="logInUser"/>
                  </div>
                  <div class="text-right mr-3 mt-2">
                    <p class="text-primary">
                      <router-link :to="{ path: '/sendemail'}"><a>Forgot Password?</a></router-link>
                    </p>
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
       title: 'sign in',
       successEmail: '',
       verifyEmailNonActive: '',
       form: {
             email: '',
             password: ''
           },
       isActive: true,
       errors: [],

      }
    },
    created(){

      if (typeof this.$route.params.email !== 'undefined') {
           this.verifyUserEmail();
     }
    },
    methods: {
         logInUser(){
           this.$store.dispatch('userInfo/login');
           var self = this;
           login(this.$data.form)

              .then((res) =>{
                console.log(res);

                  this.$store.dispatch('userInfo/loginSuccess', res);
                  this.$router.push({path: '/dashboard'});
              })
              .catch((error) =>{
              this.$store.commit('userInfo/loginFailed', error);
              })
         },
       verifyUserEmail(){

            var self = this;
            var email = this.$route.params.email;
            var token = this.$route.params.token;
            console.log(email);
               axios.post('/verifyEmail', {email: email, verifyToken: token},
               {
               headers: {
                   Authorization: 'Bearer ' + self.token,
                   'X-CSRF-TOKEN': myToken.csrfToken,

                 }
               })
                .then((res) => {
                  self.successEmail = '';
                  self.verifyEmailNonActive = '';
                  if( undefined !== res.data.verifyEmailSuccess && res.data.verifyEmailSuccess.length){
                     self.successEmail = res.data.verifyEmailSuccess;
                  }
                  else if(undefined !== res.data.verifyEmailNonActive && res.data.verifyEmailNonActive.length){
                     self.verifyEmailNonActive = res.data.verifyEmailNonActive;
                  }

               }).catch((error) => {
                 console.log(error);
             })
         }
     },
     computed: {
       alertLogout: function(){
         return this.$store.state.userInfo.alertLogout;
       }
     }
  }

</script>
<style>


.errorMsg {
  background-color: rgb(138, 40, 34);
  color: #FFFFFF;
  width: 70%;
  margin: 10px auto;
  padding: 5px;
}
</style>
