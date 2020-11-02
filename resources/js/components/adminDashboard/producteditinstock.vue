<template>
    <transition  name="fade" appear>
      <div class="product-edit-in-stock-container">

        <h5 class="product-edit-in-stock-head">Info <span class="in-stock-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-in-stock">
              <li>
                <div class="index-text index-text-in-stock" v-if="inStock" >Edited!</div>
                <input
                  v-if="!inStock"
                  :value="product.in_stock == 'null' ? '' : product.in_stock"
                  ref="inStock"/>

                  <div class="dashboard-edit-btn my-2" v-on:click="productEditInStock(product.id)" v-if="!inStock">
                    <input type="button" value="Edit In Stock" v-if="!loading"/>
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
        in_stock: null,
        inStock: false,
        loading: false,
        loadingDots: true,
      }
  },
  methods: {
    inStockHandle: function(){
        let self = this;
        setTimeout(function(){
          self.inStock = false;
          self.closeComponent();
        },2000);
    },
    productEditInStock: function(id){
      let in_stock = this.$refs.inStock.value;
      this.product.in_stock = in_stock;
      // for scope of this within axios
      let self = this;
        this.loading = true;
        axios.post('/producteditinstock',
        {
          Authorization:  this.$store.state.admin.currentAdmin.token,
          productId: id,
          in_stock: in_stock },
          )
         .then((res) => {
           if(res.data.success){
             self.inStock = true;
             self.inStockHandle();
             self.loading = false;
            }
        }).catch((error) => {
          // error handling function with error, name axios is used for, and this.
         errorHandle(error, 'Product edit in-stock', self);
      })
    },
      closeComponent: function(){
        this.$root.$emit('closeInStock', false);
      },

  }


}


</script>
<style scoped>

.product-edit-in-stock-container {
  text-align: center;
  position: absolute;
  top: 0px;
  left: 0;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-in-stock-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.in-stock-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-in-stock {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-in-stock li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-in-stock input {
  width: 100%;
}
.in-stock-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.in-stock-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
}
.index-text-in-stock {
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
