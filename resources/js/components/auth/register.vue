<template>
  <div class="non-popup-form-wrapper">

           <form id="registerForm" class="form">

              <h3>REGISTER
                <span class="crossingLine"></span>
              </h3>

               <br/>
                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">

                        <p>
                          <input id="createEmailInput1" class="form-control" v-bind:class="{formInput: isActive}"  type="email"  v-model="form.email"/>
                          <span class="labelStyle">Email</span>
                        </p>
                         <small v-if="errors.email">{{ errors.email[0] }} </small>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input id="createFirstNameInput1" class="form-control" v-bind:class="{formInput: isActive}"  type="text"  v-model="form.firstName"/>
                          <span class="labelStyle" >First Name</span>
                        </p>
                        <small v-if="errors.firstName">{{ errors.firstName[0] }} </small>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input id="createLastNameInput1" class="form-control" v-bind:class="{formInput: isActive}"   type="text"  v-model="form.lastName"/>
                          <span class="labelStyle" >Last Name</span>
                        </p>
                        <small v-if="errors.lastName">{{ errors.lastName[0] }} </small>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input id="createPasswordInput1" class="form-control" v-bind:class="{formInput: isActive}"   type="password"  v-model="form.password"/>
                          <span class="labelStyle">Password</span>
                        </p>
                        <small v-if="errors.password">{{ errors.password[0] }} </small>
                      </div>
                      </label>

                      <label class="col-xs-12 col-sm-12">
                      <div class="inputContainer">
                        <p>
                          <input id="createReEnterPasswordInput1" class="form-control" v-bind:class="{formInput: isActive}"   type="password"   v-model="form.matchPassword"/>
                          <span class="labelStyle">Re-enter Password</span>
                        </p>
                        <small v-if="errors">{{ errors[0] }} </small>
                      </div>
                      </label>

                     <br/>
                     <p class="col-xs-12 col-sm-12 mx-auto"><input type="button" class="submitBtn"  value="Register Account" v-on:click="registerUser"/></p>

              </form>


    </div>
</template>



<script>
import { register } from '../../helper/auth';

export default {

    data(){
      return{
          title: 'register',
          form:{
                email: '',
                firstName: '',
                lastName: '',
                password: '',
                matchPassword: ''
              },

          successRegister: '',
          errors: [],
          isActive: true
      }
    },
    methods: {
      registerUser: function(){

           let self = this;
             let sendEmail = this.$data.form.email;
          if(this.$data.form.password == this.$data.form.matchPassword)
          {

            axios.post('/register', this.$data.form)
             .then((response) => {

              self.$data.form.email = '';
              self.$data.form.firstName = '';
              self.$data.form.lastName = '';
              self.$data.form.password = '';
              self.$data.form.matchPassword = '';
              self.$data.isActive = false;
               alert('Your account is Registered! Please check Email "' + sendEmail + '" for Verification.');
            }).catch((error) => {
              console.log(error.response.data);
              this.errors = error.response.data.errors;

            })
          }else{

          this.errors =  ["Passwords must match"];
          console.log(this.errors);
          }
        }

      }

    }




</script>
<style>

</style>
