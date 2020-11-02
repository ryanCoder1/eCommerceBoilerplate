<template>
    <transition  name="fade" appear>
      <div class="product-edit-sale-price-container">

        <h5 class="product-edit-sale-price-head">Info <span class="sale-price-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-sale-price">
              <li>
                <div class="index-text index-text-sale-price" v-if="editSalePrice" >Edited!</div>
                <input
                  v-if="!editSalePrice"
                  :value="product.sale_price == 'null' ? '' : product.sale_price"
                  ref="salePrice"/>

                  <div class="dashboard-edit-btn my-2" v-on:click="productEditSalePrice(product.id)" v-if="!editSalePrice">
                    <input type="button" value="Edit Sale Price" v-if="!loading"/>
                    <load-dots
                     v-if="loading"
                     v-bind:loading-dots="loadingDots"
                     >
                   </load-dots>
                 </div>
               </li>
            </ul>
      </div>
   </transition>
</template>



<script>

import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'load-dots': LoadDots,
    },
    props: {
      'product': Object,
    },
    data(){
      return{
        sale_price: null,
        editSalePrice: false,
        loading: false,
        loadingDots: true,
      }
  },
  methods: {
    editSalePriceHandle: function(){
        let self = this;
        setTimeout(function(){
          self.editSalePrice = false;
          self.closeComponent();
        },2000);
    },
    productEditSalePrice: function(id){
      let sale_price = this.$refs.salePrice.value;
      this.product.sale_price = sale_price;
      // for scope of this within axios
      let self = this;
        this.loading = true;
        axios.post('/producteditsaleprice',
        {
          Authorization:  this.$store.state.admin.currentAdmin.token,
          productId: id,
          sale_price: sale_price },
          )
         .then((res) => {
           if(res.data.success){
             self.editSalePrice = true;
             self.editSalePriceHandle();
             self.loading = false;
            }
        }).catch((error) => {
          // error handling function with error, name axios is used for, and this.
         errorHandle(error, 'Product edit sale-price', self);
      })
    },
      closeComponent: function(){
        this.$root.$emit('closeSalePrice', false);
      },

  }


}


</script>
<style scoped>

.product-edit-sale-price-container {
  text-align: center;
  position: absolute;
  top: 0px;
  left: 0;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-sale-price-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.sale-price-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-sale-price {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-sale-price li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-sale-price input {
  width: 100%;

}
.sale-price-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.sale-price-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-sale-price {
  height: 200px;
  padding-top: 75px;

 }
/* TRANSITION INTO COMPONENT */
.fade-enter-active{
     transition: opacity .5s;
 }
 .fade-leave-active {
     opacity: 0;
 }
 .fade-enter, .fade-leave-to {
     opacity: 0;
 }


</style>
