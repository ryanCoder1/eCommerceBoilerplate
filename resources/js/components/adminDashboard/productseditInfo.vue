<template>
    <transition  name="fade" appear>
      <div class="product-edit-info-container">

        <h5 class="product-edit-info-head">Info <span class="info-x" v-on:click="closeComponent()">X</span></h5>
            <ul class="product-edit-info">
              <li>
                <!-- EDIT DESCRIPTION SECTION -->
                <div class="info-name">Description</div>
                <div class="info-output">{{ product.description === 'null' ? '' : product.description  }}</div>
                  <p class="info-edit-click text-center" v-on:click="editDescription()">Edit</p>
                  <edit-description
                    v-if="descriptionVis"
                    v-bind:product="product">
                  </edit-description>
                <!-- EDIT DETAILS SECTION -->
                <div class="info-name">Details</div>
                <div class="info-output">{{ product.details === 'null' ? '' : product.details }}</div>
                <p class="info-edit-click text-center" v-on:click="editDetails()">Edit</p>
                <edit-detail
                  v-if="detailVis"
                  v-bind:product="product">
                </edit-detail>
                <!-- EDIT PRICE SECTION -->
                <div class="info-name">Price</div>
                <div class="info-output">{{ product.price === 'null' ? '' : product.price  }}</div>
                <p class="info-edit-click text-center" v-on:click="editPrice()">Edit</p>
                <edit-price
                  v-if="priceVis"
                  v-bind:product="product">
                </edit-price>
                <!-- EDIT SALE_PRICE SECTION -->
                <div class="info-name">Sale Price</div>
                <div class="info-output">{{ product.sale_price === 'null' ? '' : product.sale_price  }}</div>
                <p class="info-edit-click text-center" v-on:click="editSalePrice()">Edit</p>
                <edit-sale-price
                  v-if="salePriceVis"
                  v-bind:product="product">
                </edit-sale-price>
                <!-- EDIT SALE_PRICE SECTION -->
                <div class="info-name">In Stock</div>
                <div class="info-output">{{ product.in_stock === 'null' ? '' : product.in_stock  }}</div>
                <p class="info-edit-click text-center" v-on:click="editInStock()">Edit</p>
                <edit-in-stock
                  v-if="inStockVis"
                  v-bind:product="product">
                </edit-in-stock>
               </li>
            </ul>
      </div>
   </transition>
</template>



<script>

import EditDescription from './producteditdescription.vue';
import EditDetail from './producteditdetail.vue';
import EditPrice from './producteditprice.vue';
import EditSalePrice from './producteditsaleprice.vue';
import EditInStock from './producteditinstock.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'edit-description': EditDescription,
      'edit-detail': EditDetail,
      'edit-price': EditPrice,
      'edit-sale-price': EditSalePrice,
      'edit-in-stock': EditInStock,
    },
    props: {
      'product': Object,
    },
    data(){
      return{
        descriptionVis: false,
        detailVis: false,
        priceVis: false,
        salePriceVis: false,
        inStockVis: false,
        loading: false,
        loadingDots: false,
      }
  },
  created(){
    this.$root.$on('closeDescription', (value) =>{
       this.descriptionVis = value;
    }),
    this.$root.$on('closeDetail', (value) =>{
       this.detailVis = value;
    }),
    this.$root.$on('closePrice', (value) =>{
       this.priceVis = value;
    }),
    this.$root.$on('closeSalePrice', (value) =>{
       this.salePriceVis = value;
    }),
    this.$root.$on('closeInStock', (value) =>{
       this.inStockVis = value;
    })
  },
  methods: {
    editDescription: function(){
      this.descriptionVis = true;
    },
    editDetails: function(){
      this.detailVis = true;
    },
    editPrice: function(){
      this.priceVis = true;
    },
    editSalePrice: function(){
      this.salePriceVis = true;
    },
    editInStock: function(){
      this.inStockVis = true;
    },
    closeComponent: function(){
      this.$root.$emit('closeInfo', false);
    }
  }


}


</script>
<style scoped>
.product-edit-info-container {
  text-align: center;
  position: absolute;
  top: -15px;
  left: 20%;
  z-index: 10;
  background-color: #FFFFFF;
  padding: 10px 0;
  box-shadow: 0 0 4px 1px rgba(0,0,0, .1);
}
.product-edit-info-head {
  position: relative;
  left: -2%;
  color: #213B50;
  width: 100%;
}
.info-x {
  position: absolute;
  top: -2px;
  right: 2px;
  font-size: 17px;
  color: rgb(180, 180, 180);
  cursor: pointer;
}
.product-edit-info {
  height: 350px;
  list-style: none;
  overflow-y: scroll;
  text-align: left;
  padding: 0;
  margin: 0;
}
.product-edit-info li {
  width: 300px;
  padding: 0;
  margin: 20px 10px;
}
.product-edit-info img {
  width: 100%;
  height: 200px;
  margin: 0;
  padding: 0;
  background-color: rgb(255, 255, 255);

}
.info-edit-click {
  text-transform: uppercase;
  text-decoration: underline;
}
.info-name {
  display: inline;
  background-color: #f2f2f2;
  padding: 5px 10px 5px 5px;

}
.info-output {
  padding: 10px 0;
  text-align: center;
  border-bottom: solid thin #f2f2f2;
  margin-bottom: 10px;
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
