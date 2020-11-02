<template>
  <div>
    <div class="product-show-container">
      <h4 class="product-show-head">
        Check  up to {{ productSaveLength }} Products to show on Home page
        <br/>
        <span :class="{chosenOver: isActive}">Chosen = {{ productsChecked }}</span>
        <div class="dashboard-add-btn" v-if="saveBtnShow" v-on:click="saveProducts()">
          <input type="button"   value="Save Product list" v-if="!loading && !updatedVis"/>
          <load-dots
           v-if="loading"
           v-bind:loading-dots="loadingDots"
           >
          </load-dots>
           <p class="mt-2 featured-updated-grow" v-if="!loading && updatedVis">{{ updated }}</p>
       </div>
      </h4>
      <ul class="product-show-ul">

        <li v-for="(product, index) in products">
            <div class="product-show-parent">

                <div class="product-table-td1" >
                  <input type="checkbox" :checked="check" :ref="'prod' + index" v-on:change="saveProduct(index, product.id)"/>
                </div>
                <div class="product-show-image-td">
                    <div class="product-show-image" v-if="product.image_name != null">
                      <img :src="'../storage/images/' + product.image_path + '/' + product.image_name" alt="product image">
                    </div>
                 </div>
                 <div class="product-show-title">{{ product.title}}</div>
             </div>
           </li>
        </ul>
      </div>
        <!-- Error/Success messages from api -->
        <p class="bg-danger text-light p-2 ml-4 d-inline" v-if="errors != null">{{ errors }}</p>
        <p class="bg-success text-light p-2 my-2 ml-4 d-inline" v-if="success != null">{{ success }}</p>


  </div>
</template>

<script>
import { errorHandle } from '../../helper/errors';
import LoadDots from '../pages/loaddots.vue';

  export default {
    components: {
      'load-dots': LoadDots,
    },
    props: {
      'products': Array,
    },
    data(){
      return {
        productsChecked: 0,
        check: false,
        saveBtnShow: false,
        errors: null,
        success: null,
        loading: false,
        loadingDots: true,
        updatedVis: false,
        updated: 'Updated!',
        isActive: false,
        productList: [],
        productSaveLength: 12,
      }
    },
    mounted() {
      this.adjustSideBarHeight();
      let self = this;
      setTimeout(function(){
        self.getProductsOnHome();
      }, 300);

    },
    methods: {
      adjustSideBarHeight: function(elem){
        this.$root.$emit('adjustSideBarHeight', elem);
      },
      addProduct: function(index, id){
         this.productList.push({
           index: index,
           productId: id,
         });
         console.log(this.productList);
      },
      removeProduct: function(index, id){
        for(let i = 0; i < this.productList.length; i++){
          if(this.productList[i].index == index){
            this.productList.splice(i, 1);
          }
        }
      },
      addProductsIn: function(){
        for(let i = 0; i < this.productsIn.length; i++){
          this.productList.push({
            index: this.productsIn[i].index_of_product,
            productId: this.productsIn[i].product_id,
          });
          this.$refs['prod' + this.productsIn[i].index_of_product][0].checked = true;
        }

      },
      clearProducts: function(index, id){
        for(let i = 0; i < this.productList.length; i++){
            this.productList.splice(i, 1);
        }
        console.log(this.productList);
      },
      saveProduct: function(index, id){
        // the product is checked(index)
          if(this.$refs['prod' + index][0].checked){
            // add 1 to when checked is true
            this.productsChecked++;
            // if between 1 and productSaveLength, run methods
            if(this.productsChecked > 0 && this.productsChecked <= this.productSaveLength){
              this.saveBtnShow = true;
              this.addProduct(index, id);
              this.adjustSideBarHeight();
            }else{
              if(this.productsChecked > this.productSaveLength){
                // make isActive true and turn on style class of red font
                this.addProduct(index, id);
                this.isActive = true;
              }
              this.saveBtnShow = false;
            }

          }else{
            // subtract 1 on checked(index) == false
            this.productsChecked--;
            if(this.productsChecked > 0 && this.productsChecked <= this.productSaveLength){
              this.saveBtnShow = true;
              this.removeProduct(index, id);
            }else{
              this.saveBtnShow = false;
            }
            if(this.productsChecked == 0 ){
              this.removeProduct(index, id);
            }
            if(this.productsChecked <= this.productSaveLength){
              this.isActive = false;
            }
          }

      },
      removeUpdated: function(){
            let self = this;
            setTimeout(function(){
               self.updatedVis = false;
               self.saveBtnShow = false;
            }, 2000);
      },
      saveProducts: function(){
          // for scope of this within axios
          let self = this;

          let count = 0;
          let fd = new FormData();

            for(let i = 0; i < self.productList.length; i++){
                  fd.append('productId' + count, self.productList[i].productId);
                  fd.append('indexOfProduct' + count, self.productList[i].index);
                  count++;
              }
              fd.append('count', count);
              fd.append('Authorization', self.$store.state.admin.currentAdmin.token)

            axios.post('/productshomestore', fd,
              )
             .then((res) => {
               if(res.data.success ){
                  self.loading = false;
                  self.updatedVis = true;
                  self.removeUpdated();
                  self.clearProducts();
                  self.getProductsOnHome();
                }else{
                  self.noProduct = false;
                }

            }).catch((error) => {
              // error handling function with error, name axios is used for, and this.
             errorHandle(error, 'Product Get in Edit', self);
            })
        },
        getProductsOnHome: function(){
            // for scope of this within axios
            let self = this;
              axios.post('/productshomeshow',
              {
                Authorization: self.$store.state.admin.currentAdmin.token
              }
                )
               .then((res) => {
                 if(res.data.products !== undefined && res.data.products.length ){
                   console.log(res.data);
                   self.productsIn = res.data.products;
                   setTimeout(function(){
                        self.addProductsIn();
                   },400);

                   self.productsChecked = res.data.products.length;
                  }
              }).catch((error) => {
                // error handling function with error, name axios is used for, and this.
               errorHandle(error, 'Product Get in Edit', self);
              })
          },

    }
  }
</script>

<style lang="css">
.product-show-container {
  max-width: 400px;
  width: 100%;
  padding: 0;
  margin: 0 auto;
  border: solid 10px #FFFFFF;
  box-shadow: 0 0 20px 1px rgba(0,0,0, .2);
}
.product-show-head {
  font-size: 20px;
  text-align: center;
  overflow-x: hidden;
}
.product-show-ul {
  width: 100%;
  list-style: none;
  padding: 0;
  height: 450px;
  overflow-y: scroll;

}
.product-show-ul li {
  width: 100%;
  padding: 0;
  border-bottom: solid 1px #dbdbdb;
}
.product-show-parent {
  width: 100%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
}

.product-table-td1 {
  padding: 10px 15px !important;
}
.product-show-image-td {
  text-align: center !important;
  max-width: 125px;
  width: 100%;
}
.product-show-image img {
  width: 100px;
  height: 100px;
}
.product-show-title {
  max-width: 125px;
  width: 100%;
  text-align: left !important;
  text-transform: capitalize;
  color: #213B50;
  font-weight: bold;
}
.chosenOver {
  color: rgb(195, 44, 62);
}
</style>
