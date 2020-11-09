<template>
      <div id="dashboardProductsCreateContainer" class="dashboard-page-containers">
         <h3>Products Create</h3>
          <h4 class="text-secondary" v-if="!noCategory">There haves to be categories for products in order to create products.</h4>
            <form id="dashboardProductsCreate" class="form" v-if="noCategory">

                      <label class="col-12 mx-auto">
                        <div class="inputContainer">
                          <p>
                            <select class="form-control formInput" name="state" v-on:change="clearErrors()" v-model="form.category_id">
                            <option value="" disabled>Choose Category</option>
                            <option :value="category.id" v-for="(category, index) in categories">{{ category.category }}</option>
                         </select>
                        <span class="labelStyle">Category</span>
                       </p>
                       </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                       <div class="inputContainer">
                         <p>
                           <input class="form-control" v-bind:class="{formInput: isActive}"  type="text" v-model="form.product_code" />
                           <span class="labelStyle">Product Code</span>
                         </p>
                       </div>
                     </label>

                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"  type="text"  v-model="form.title"/>
                         <span class="labelStyle" >Title</span>
                       </p>
                     </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                       <div class="inputContainer">
                         <p>
                           <textarea class="form-control" v-bind:class="{formInput: isActive}" v-model="form.description" ></textarea>
                           <span class="labelStyle" >Description</span>
                         </p>
                       </div>
                     </label>
                     <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.details"/>
                         <span class="labelStyle" >Details for search (e.g. hat, picture frame)</span>
                       </p>
                     </div>
                   </label>
                   <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.price"/>
                         <span class="labelStyle" >Main Price (individual product groups' price will over ride main price)</span>
                       </p>
                     </div>
                   </label>
                   <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.sale_price"/>
                         <span class="labelStyle" >Main Sale Price (individual product groups' sale price will over ride main sale price)</span>
                       </p>
                     </div>
                   </label>
                   <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"   type="text" v-model="form.in_stock"/>
                         <span class="labelStyle" >Main In Stock (individual product groups' have an In-stock option alsow)</span>
                       </p>
                     </div>
                   </label>
                   <label class="col-xs-12 col-sm-12">
                     <div class="inputContainer">
                       <p>
                         <input class="form-control" v-bind:class="{formInput: isActive}"  type="color" v-on:change="displayColorHex()" v-model="form.color" />
                         <span class="labelStyle">Product color (precise colors please. Hexidecimal)</span>
                       </p>
                     </div>
                   </label>
                   <div class="groups-bg-color-hex ml-4 mb-4" v-bind:style="{backgroundColor: colorHex }" v-if="colorHexCheck"></div>

                     <img class="image-upload ml-4 mb-2" v-if="url" :src="url" />
                     <div class="input-container-file">
                       <p>
                         <input class="inputfile" id="file" ref="file"  type="file" v-on:change="handleFile()" />
                         <label for="file">
                           <i class="fa fa-upload file-icon"></i>
                           Main Image Upload
                         </label>
                         <i class="fa fa-check text-success check-icon" v-if="imageChosen"></i>
                       </p>
                     </div>

                     <!-- Error/Success messages from api -->
                     <p class="error-msg" v-if="errors != null">{{ errors }}</p>
                     <p class="success-msg" v-if="success != null">{{ success }}</p>


                    <div class="dashboard-page-btn" v-on:click="saveProduct()">
                      <input type="button"   value="Save Product" v-if="!loading"/>
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
        noCategory: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        imageChosen: false,
        isActive: false,
        categories: [],
        form: {
          category_id: null,
          product_code: null,
          title: null,
          description: null,
          details: null,
          price: null,
          sale_price: null,
          in_stock: null,
          color: null,
        },
        image_file: '',
        url: null,
        colorHexCheck: false,
        colorHex: null,
      }

  },
  mounted(){

    // retrieve categories for products on page load
     this.getCategories();
     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();

  },
  watch: {
    noCategory: function(newNoCategory, oldNoCategory){
      // run function to get height of template to send to parent sidebarmenu
        console.log(oldNoCategory + ' and ' + newNoCategory);
      if(newNoCategory){
        let self = this;
        setTimeout(function(){
           self.templateHeight();
        }, 500);

      }
    }
  },
  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    clearErrors: function(){
      this.errors = null;
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardProductsCreateContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    handleFile: function(){
      this.image_file = this.$refs.file.files[0];
      this.url = URL.createObjectURL(this.$refs.file.files[0]);
      this.imageChosen = true;
      let self = this;
      setTimeout(function(){
          self.templateHeight();
      }, 100);

    },
    validation: function(){
      if(this.$data.form.category_id == null){
            this.errors = 'A category must be chosen for product.';
            return false;
      }else {
          return true;
      }

    },
    displayColorHex: function(){
        // display the color of input color chosen
        this.colorHexCheck = true;
        this.colorHex = this.$data.form.color;

    },
    clearFields: function(){
      this.$data.form.category_id = null;
      this.$data.form.product_code = null;
      this.$data.form.title = null;
      this.$data.form.description = null;
      this.$data.form.details = null;
      this.$data.form.price = null;
      this.$data.form.sale_price = null;
      this.$data.form.in_stock = null;
      this.$data.form.image_file = '';
      this.$data.form.color = null;
      this.colorHexCheck = false;
      this.colorHex = null;
      this.$data.isActive = false;
      this.imageChosen = false;
      this.url = null;
      this.$refs.file.value = '';
    },
      saveProduct: function(){
        if(!this.validation()){
            return false;
          }
          // for scope of this within axios
          let self = this;
          this.loading = true;

          let fd = new FormData();
              fd.append('file',this.image_file)
              fd.append('category_id',this.$data.form.category_id)
              fd.append('product_code',this.$data.form.product_code)
              fd.append('title',this.$data.form.title)
              fd.append('description',this.$data.form.description)
              fd.append('details',this.$data.form.details)
              fd.append('price',this.$data.form.price)
              fd.append('sale_price',this.$data.form.sale_price)
              fd.append('in_stock', this.$data.form.in_stock)
              fd.append('color', this.$data.form.color)
              fd.append('Authorization', this.$store.state.admin.currentAdmin.token)

            axios.post('/productstore',fd,
            {
             headers: {
                      'Content-Type': 'multipart/form-data',
                      }
             })
             .then((res) => {
               if(res.data.error == false){
                  self.$router.push('/admin/logout');
                }
               if(res.data.success){

                  self.clearFields();
                  self.loading = false;
                  self.success = 'Product was saved successfully!';
             }
            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Save', self);
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
                    self.noCategory = true;

                  }else{
                    self.noCategory = false;
                  }

              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Product Get Categories', self);
              })
          }
    }


}


</script>
<style scoped>
.groups-bg-color-hex {
  width: 26px;
  height: 26px;
  box-shadow: 0 4px 3px 1px rgba(0,0,0, .5);
  border-radius: 50%;
}
.image-upload {
  width: 200px;
  height: 250px;
}
</style>
