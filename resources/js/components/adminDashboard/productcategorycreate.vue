<template>
  <div id="dashboardProductCategoriesContainer" class="dashboard-page-containers">
     <h3>Product Category Create</h3>
      <!-- <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to create a product group.</h4> -->
        <form id="dashboardProductsCategories" class="form">

                  <label class="col-12 mx-auto">
                    <div class="inputContainer">
                      <p>
                        <select class="form-control formInput" name="state" v-on:change="clearErrors()">
                        <option value="" selected disabled>Created Categories</option>
                        <option v-for="(category, index) in categories">{{ category.category }}</option>
                     </select>
                    <span class="labelStyle">Created Categories (for show)</span>
                   </p>
                   </div>
                 </label>
                 <label class="col-xs-12 col-sm-12">
                   <div class="inputContainer">
                     <p>
                       <input class="form-control" v-bind:class="{formInput: isActive}"  type="text" v-on:keyup="clearErrors()"  v-model="form.category" />
                       <span class="labelStyle">Name of new Product Category</span>
                     </p>
                   </div>
                 </label>
                 <label class="col-xs-12 col-sm-12">
                   <div class="inputContainer">
                     <p>
                     <textarea class="form-control category-textarea"
                       v-bind:class="{formInput: isActive}"
                       v-model="form.caption"
                       v-on:keyup="captionCount()"
                       >
                       </textarea>
                       <span class="labelStyle" >Shows on category/products. Explains category. (not necessary)</span>
                       <span >
                         There are
                         <span class="text-success" v-if="captionLength >= 0 && captionLength <= captionCharCount">{{ captionLength }} characters with a limit of {{ captionCharCount }}.</span>
                         <span class="text-danger" v-if="captionLength > captionCharCount">{{ captionLength }} must be {{ captionCharCount }} characters or below!</span>

                       </span>
                     </p>
                   </div>
                 </label>

                 <!-- Error/Success messages from api -->
                 <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                 <p class="success-msg" v-if="success != null">{{ success }}</p>

                <div class="dashboard-page-btn" v-on:click="saveCategory()" v-if="errorsCaption == null">
                  <input type="button" value="Save Product Category" v-if="!loading"/>
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
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        isActive: false,
        categories: [],
        form: {
          category: null,
          caption: '',
          Authorization:  this.$store.state.admin.currentAdmin.token,
        },
        captionLength: 0,
        captionCharCount: 255,
        errorsCaption: null,

      }

  },
  mounted(){

    // retrieve categories for products on page load
     this.getCategories();
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();
  },

  methods: {
    captionCount: function(index){
      this.clearErrors();
      // count the characters in this.caption on caption textarea
      this.captionLength = this.$data.form.caption.length;
      // if caption length gtr 55 hide image upload until user meets below 56 character limit
      if(this.captionLength > this.captionCharCount){
        this.errorsCaption = 'Image won\'t load with caption above 55 characters!';
      }else{
        this.errorsCaption = null;
      }
    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Categories');
    },
    clearErrors: function(){
      this.errors = null;
      this.success = null;
    },
    validation: function(){
        if(this.$data.form.category == null){
              this.errors = 'The category field can\'t be empty';
              return false;
        }
        else {
            return true;
        }

    },

      saveCategory: function(){

        if(!this.validation()){
            return false;
          }
          // for scope of this within axios
          let self = this;
          this.loading = true;


            axios.post('/productcategorystore',this.$data.form,
            )
             .then((res) => {
             if(res.data.in_db){
                 self.errors = 'That category is already in the records.';
                 self.loading = false;
               }
              else if(res.data.success){

                  self.$data.form.category = null;
                  self.$data.form.caption = '';
                  self.captionLength = 0;
                  self.$data.isActive = false;
                  self.loading = false;
                  self.success = 'Product Category was saved successfully!';
                  self.getCategories();
             }
             else if (!res.data.success){
               self.$store.dispatch('userErrors/featureNeedsService', 'Product Category save');
               self.$router.push('/featureneedsservicedashboard');
             }

            }).catch((error) => {
              if(error.response.status == 401){
                 this.$router.push('/admin/logout');
               }
               if(error.response.status == 500){
                  self.$store.dispatch('userErrors/featureNeedsService', 'Product Category save');
                  self.$router.push('/featureneedsservicedashboard');
                }
            })

        },
        getCategories: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/productcategoryretrieve', {Authorization:  this.$store.state.admin.currentAdmin.token},
                )
               .then((res) => {
                 console.log(res);
                 if(res.data.categories != undefined && res.data.categories.length ){
                      self.categories = res.data.categories;
                  }

              }).catch((error) => {

                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Product categories create', self);

              })
          }

    }


}


</script>
<style>
 .category-textarea {

 }
</style>
