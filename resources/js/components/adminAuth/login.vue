<template>
  <div class="non-popup-form-wrapper">
        <h3 class="dashboard-not-valid" v-if="notValid != null">{{ notValid }}</h3>
        <!-- v-if="showForm" -->
           <form id="adminLoginForm" class="form" >

              <h3>Dashboard Log in
                <span class="crossingLine"></span>
              </h3>
              <small class="text-primary p-2">{{ logoutAlert }}</small>
               <br/>
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"  type="email"  v-model="form.email"/>
                          <span class="labelStyle">Email</span>
                        </p>
                      </div>
                      </label>
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"   type="password"  v-model="form.password"/>
                          <span class="labelStyle" >Password</span>
                        </p>
                      </div>
                      </label>
                      <!-- Error/Success messages from api -->
                      <p class="error-msg" v-if="errors != null">{{ errors }}</p>

                     <br/>
                     <div class="admin-login-btn col-xs-12 col-sm-12 mx-auto" v-on:click="logInDashboard()">
                       <input type="button" value="Log in" v-if="!loading"/>
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
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
          showForm: false,
          notValid: null,
          form:{
                email: null,
                password: null,
              },
          isActive: true,
          errors: null,
          loading: false,
          loadingDots: true,
      }
    },
    mounted(){
      // this.init();
    },
    computed: {
      logoutAlert: function(){
        if(this.$store.state.admin.alertLogout != ''){
          return this.$store.state.admin.alertLogout;
        }
      }
    },
    methods: {
      // test whether there is a route.param or not
      init: function(){

        if(this.$route.params.email != undefined){
          this.testValidParams();
        }
        if(this.$route.path == '/admin/login'){
          this.showForm = true;
          this.notValid = '';
        }
      },
      logInDashboard: function(){
        // for scope of this within axios
        let self = this;
        this.loading = true;

          if(this.$data.form.email != '')
          {
            axios.post('/adminlogin', this.$data.form)
             .then((res) => {
               if(res.data.success){
                   self.$store.dispatch('admin/loginSuccess', res.data);
                   self.$router.push({path: '/dashboard'});
             }
             else if(!res.data.successpassword){
                   self.errors = 'Password does not match account.';
               }
            else if(!res.data.successemail){
                  self.errors = 'No such email exists. An Admin in the Dashboard needs to invite a user for account creation.';
              }

            }).catch((error) => {
              this.errors = error.response.data.errors;
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Login issues', self);

            })
          }else{
          this.errors =  ["Email must be a valid format"];
          }
        },
        // test validity of $route.params of privilege and token before showing form to create account

        testValidParams: function(){

            let self = this;

              axios.post('/verifyadminloginparams', {
                verifyToken: self.$route.params.verifyToken,
                email: self.$route.params.email,
              })
               .then((res) => {
                 if(res.data.verifyValid){
                    self.showForm = true;
               }else{
                  self.notValid = 'Sorry, this page is no longer valid!';
               }
              }).catch((error) => {
                console.log(error.response.data);
                this.errors = error.response.data.errors;

              })

          },
          validateRegister: function(){
            if(!this.validEmail()){
                  this.errors = 'Email must be a valid format';
                  return false;
                }
        else if(this.$data.form.password != this.$data.form.reEnterPassword){
                  this.errors = 'Passwords must match';
                  return false;
                }
          else{
                  return true;
                }

          },
          validEmail: function () {
              var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(this.$data.form.email);
          }


      }

    }

</script>
<style>
.dashboard-not-valid {
  margin-top: 200px;
  color: rgb(110, 110, 110);
}

</style>
