<template>
  <div class="non-popup-form-wrapper">
        <h3 v-if="notValid != ''">{{ notValid }}</h3>
           <form id="adminCreateForm" class="form" v-if="showForm">

              <h3>Dashboard create account
                <span class="crossingLine"></span>
              </h3>

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
                          <input class="form-control" v-bind:class="{formInput: isActive}"  type="text"  v-model="form.firstName"/>
                          <span class="labelStyle" >First Name</span>
                        </p>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"   type="text"  v-model="form.lastName"/>
                          <span class="labelStyle" >Last Name</span>
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
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"   type="password"  v-model="form.reEnterPassword"/>
                          <span class="labelStyle" >Re-enter password</span>
                        </p>
                      </div>
                      </label>
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input class="form-control" v-bind:class="{formInput: isActive}"   type="text"  v-model="form.jobTitle"/>
                          <span class="labelStyle" >Job title</span>
                        </p>
                      </div>
                      </label>
                      <!-- Error/Success messages from api -->
                      <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                      <p class="error-msg" v-if="errorEmail">Email already exists. <br/>

                        Try <router-link :to="{ name: 'DashboardLogin', path: '/dashboard/login' }"><a>Dashboard log in</a></router-link></p>
                     <p class="admin-create-btn" v-on:click="registerDashboard()">
                       <input type="button"   value="Create Dashboard Account" v-if="!loading"/>
                       <load-dots
                        v-if="loading"
                        v-bind:loading-dots="loadingDots"
                        >
                      </load-dots>
                     </p>

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
          notValid: '',
          errorEmail: false,
          form:{
                email: null,
                firstName: null,
                lastName: null,
                jobTitle: null,
                password: null,
                reEnterPassword: null,
                privilege: this.$route.params.privilege,
                invitedBy: null,
              },
          isActive: true,
          errors: null,
          loading: false,
          loadingDots: true,
      }
    },
    mounted(){
      this.testValidParams();
    },
    methods: {
      registerDashboard: function(){
          // for scope of this within axios
          let self = this;
          this.loading = true;
          // method returns true if no errors
          if(!this.validateRegister()){
            self.loading = false;
            return false;
          }

            axios.post('/adminregister', this.$data.form)
             .then((res) => {
               if(res.data.emailexists){
                 self.errors = null;
                 self.loading = false;
                 self.errorEmail = true;
               }
               else if(res.data.accountcreated){
                  self.$data.form.email = '';
                  self.$data.form.firstName = '';
                  self.$data.form.lastName = '';
                  self.$data.form.privilege = '';
                  self.$data.form.password = '';
                  self.$data.form.reEnterPassword = '';
                  self.$data.form.jobTitle = '';
                  self.$data.isActive = false;
                  self.loading = false;
                  alert('A verify email has been sent successfully!');

             }
             else{
                self.errors = 'Could not be mailed';
                self.loading = false;
             }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Create Account', self);
            })

        },
        // test validity of $route.params of privilege and token before showing form to create account

        testValidParams: function(){

            let self = this;

              axios.post('/verifyadmininvite', {
                inviteToken: self.$route.params.adminVerifyToken,
                privilege: self.$route.params.privilege,
              })
               .then((res) => {
                 if(res.data.inviteValid){
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


</style>
