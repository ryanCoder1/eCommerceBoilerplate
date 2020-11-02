<template>
      <div id="dashboardSliderSpecialsContainer" class="dashboard-page-containers">
         <h3>Slider Specials</h3>
          <h4 class="text-secondary" v-if="!noProduct">There haves to be products in order to create a slider specials.</h4>
          <h5 class="text-secondary slider-featured-checkbox h5">
            <div class="slider-featured-checkbox-span">
              Slider Specials
              <span v-if="!checked"> is off </span>
              <span v-if="checked"> is on </span>
              <span v-if="showSave">if saved</span>
            </div>
            <div class="checkbox slider-featured-checbox-input">
              <input type="checkbox" id="switch" :checked="checked" v-on:change="showSaveBtn()"/>
              <label for="switch">Toggle</label>
            </div>
            <div class="dashboard-edit-btn slider-featured-checbox-span" v-if="showSave">
              <div class="dashboard-page-btn" v-on:click="saveSpecialsUse()">
                <input type="button"   value="Save Changes" v-if="!loading && !updatedVis"/>
                <load-dots
                 v-if="loading"
                 v-bind:loading-dots="loadingDots"
                 >
               </load-dots>
                 <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
              </div>
            </div>
          </h5>
            <form id="dashboardProductsCreate" class="form" v-if="noProduct">

                    <label class="col-12 mx-auto">
                      <div class="inputContainer">
                        <p>
                          <select class="form-control formInput" name="state" v-on:change="selectSliderProduct($event)" v-model="product_id">
                          <option value="" disabled>Choose Product</option>
                          <option :value="product.id" v-for="(product, index) in products" :key="index">{{ product.title }}</option>
                       </select>
                      <span class="labelStyle">Choose Products for Slider Specials</span>
                     </p>
                     </div>
                   </label>
                   <div class="specials-list-container" v-on:click="showCurrentSpecials()"><span class="specials-list-plus">Current Slider Specials +</span>
                      <div class="specials-list" v-if="showCurrent && noSpecials">There isn't a slider specials recorded.</div>
                       <div class="specials-list" v-if="showCurrent && !noSpecials" v-for="(special, index) in specials" :key="index">
                         <span class="specials-number">{{ index + 1 }}.</span>
                         <span class="specials-name">{{ special.title}}</span>
                       </div>
                   </div>
                   <div class="specials-list-container" v-if="dataInserted">Slider Specials
                       <div class="specials-list" v-if="dataInserted" v-for="(dataField, index) in dataFields" :key="index">
                         <span class="specials-number">{{ index + 1 }}.</span>
                         <span class="specials-name">{{ dataField.productName}}</span>
                       </div>
                   </div>

                     <!-- Error/Success messages from api -->
                     <div class="pt-3">
                       <p class="bg-danger text-light p-1 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
                       <p class="bg-success text-light p-1 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>
                     </div>
                    <div class="dashboard-page-btn" v-on:click="configSpecials()">
                      <input type="button"   value="Save Slider Specials" v-if="!loading"/>
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
import { anchorsPage } from '../../helper/anchor.js';
import { errorHandle } from '../../helper/errors';
export default {
    components: {
      'load-dots': LoadDots,
    },
    data(){
      return{
        noProduct: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        dataFields: [],
        dataChosen: false,
        dataCount: 0,
        isActive: false,
        products: [],
        specials: [],
        product_id: null,
        currentIndex: '',
        dataInserted: false,
        showCurrent: false,
        noSpecials: false,
        showSave: false,
        checked: false,
        updated: 'Updated!',
        updatedVis: false,

      }

  },
  mounted(){
    this.getProducts();
    this.getSpecials();
    this.getSpecialsUse();
    // call method to alert sidemenu that the current page is in a sub menu.
    this.pageOpen();
  },
  methods: {
    showSaveBtn: function(){
      this.showSave = true;
      this.checked = !this.checked;
    },
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Slides');
    },
    showCurrentSpecials: function(){
      this.showCurrent = !this.showCurrent;
      this.success = null;
      this.errors = null;
      let self = this;
      setTimeout(function(){
        self.templateHeight();
      },100);

    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardSliderSpecialsContainer').offsetHeight;
      this.sideBarHeight(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    selectSliderProduct: function(event){
      this.currentIndex++;
      if(this.currentIndex <= 7){
          let index = event.target.selectedIndex - 1;
          this.dataFields.push(
            {
              productId: this.product_id,
              productName: this.products[index].title,
            }
          );
          this.dataInserted = true;
          this.success = null;
          this.errors = null;
          this.templateHeight();
      }else{
        this.errors = '7 products is the limit for a slider specials';
      }
    },
    configSpecials: function(){
        // retrieve all dataFields productId into comma format for DB table
        let dfCommaList = '';
        for(let i = 0; i < this.dataFields.length; i++){
            dfCommaList += this.dataFields[i].productId + ',';
        }
        this.saveSpecials(dfCommaList);
    },
    removeUpdated: function(){
          let self = this;
          setTimeout(function(){
             self.updatedVis = false;
             self.showSave = false;
          }, 2000);
    },
    getProducts: function(){
        // for scope of this within axios
        let self = this;

          axios.post('/productsretrieve',
          {
            Authorization:  this.$store.state.admin.currentAdmin.token
          })
           .then((res) => {
             console.log(res);
             if(res.data.products != undefined && res.data.products.length ){
                self.products = res.data.products;
                self.noProduct = true;

              }else{
                self.noProduct = false;
              }

          }).catch((error) => {
            // error handling function with error, name axios is used for, and this.
           errorHandle(error, 'Get Products for Specials', self);
          })
      },
      saveSpecials: function(list){
          this.errors = null;
          // for scope of this within axios
          let self = this;

            axios.post('/specialsstore',
            {
              Authorization:  this.$store.state.admin.currentAdmin.token,
              product_ids: list,
            })
             .then((res) => {
               if(res.data.success){
                  self.success = 'Slider Specials was added successfully!';
                  self.dataFields = [];
                  self.dataInserted = false;
                  self.product_id = null;
                  self.getSpecials();
                }

            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Save Specials', self);
            })
        },

        getSpecials: function(){
            // for scope of this within axios
            let self = this;

              axios.post('/specialsshow',
              {
                Authorization:  this.$store.state.admin.currentAdmin.token,
              })
               .then((res) => {
                 if(res.data.specials.length && res.data.specials !== undefined){
                    self.specials = res.data.specials;
                    self.noSpecials = false;
                    self.showCurrent = false;
                  }else {
                    self.noSpecials = true;
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Get Specials', self);
              })
          },
          getSpecialsUse: function(){
              // for scope of this within axios
              let self = this;

                axios.post('/specialsuse',
                {
                  Authorization:  this.$store.state.admin.currentAdmin.token
                },
                  )
                 .then((res) => {
                   console.log(res.data.using);
                   if(res.data.using != undefined && res.data.using.length ){
                       if(res.data.using[0].using == 0){
                         self.checked = false;
                       }else{
                         self.checked = true;
                       }
                    }
                }).catch((error) => {
                  // error handling function with error, name axios is used for, and this.
                 errorHandle(error, 'Get Specials Use', self);
                })
            },
            saveSpecialsUse: function(){
                // for scope of this within axios
                let self = this;

                  axios.post('/specialsuseupdate',
                  {
                    using: self.checked,
                    Authorization:  self.$store.state.admin.currentAdmin.token
                  },
                    )
                   .then((res) => {
                     if(res.data.success){
                       self.loading = false;
                       self.updatedVis = true;
                       self.removeUpdated();
                     }

                  }).catch((error) => {
                    // error handling function with error, name axios is used for, and this.
                   errorHandle(error, 'Save Specials Use', self);
                  })
              },

  }


}


</script>
<style>
.specials-list-container {
  padding: 10px 0 10px 15px;
  margin:  10px 15px;
  box-shadow: 0 3px 10px 1px rgba(0,0,0, .2);
  font-size: 18px;
  color: #213B50;
}
.specials-list {
  padding: 10px 0;
  margin: 10px 15px 0 15px;
  border-bottom: solid thin  #e8e8e8;
}

.specials-number {
  color: #bfbdbd;
}
.specials-name {
  margin-left: 20px;
  text-transform: capitalize;
}
.specials-list-plus {
  cursor: pointer;
}
</style>
