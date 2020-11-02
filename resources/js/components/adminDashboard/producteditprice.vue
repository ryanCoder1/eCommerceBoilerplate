<template>
    <transition  name="fade" appear>
      <div class="product-edit-price-container">

        <h5 class="product-edit-price-head">Info <span class="price-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-price">
              <li>
                <div class="index-text index-text-price" v-if="editPrice" >Edited!</div>
                <input
                  v-if="!editPrice"
                  ref="price"
                  :value="product.price == 'null' ? '' : product.price"
                  />
                  <div class="dashboard-edit-btn my-2" v-on:click="productEditPrice(product.id)" v-if="!editPrice">
                    <input type="button" value="Edit Price" v-if="!loading"/>
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
        price: null,
        editPrice: false,
        loading: false,
        loadingDots: true,
      }
  },
  methods: {
    editPriceHandle: function(){
        let self = this;
        setTimeout(function(){
          self.editPrice = false;
          self.closeComponent();
        },2000);
    },
    productEditPrice: function(id){
      let price = this.$refs.price.value;
      this.product.price = price;
      // for scope of this within axios
      let self = this;
        this.loading = true;
        axios.post('/producteditprice',
        {
          Authorization:  this.$store.state.admin.currentAdmin.token,
          productId: id,
          price: price
        },
          )
         .then((res) => {
           if(res.data.success){
             self.editPrice = true;
             self.editPriceHandle();
             self.loading = false;
            }
        }).catch((error) => {
          // error handling function with error, name axios is used for, and this.
         errorHandle(error, 'Product edit price', self);
      })
    },
      closeComponent: function(){
        this.$root.$emit('closePrice', false);
      },
  }
}


</script>
<style scoped>

.product-edit-price-container {
  text-align: center;
  position: absolute;
  top: 0px;
  left: 0;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-price-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.price-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-price {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-price li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-price input {
  width: 100%;

}
.price-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.price-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-price {
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
