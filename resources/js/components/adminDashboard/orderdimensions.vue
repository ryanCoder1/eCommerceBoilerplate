<template>
      <div id="dashboardOrderContainer" class="dashboard-page-containers">
         <h3>Order dimensions</h3>
          <h4 class="order-btn text-secondary" v-on:click="toggleAdd()">
             Add Dimensions
             <span class="order-span" v-if="addVis">-</span>
             <span class="order-span" v-if="!addVis">+</span>
          </h4>
          <h4 class="order-btn text-secondary" v-on:click="toggleOrder()">
            Order Dimensions
            <span class="order-span" v-if="orderVis">-</span>
            <span class="order-span" v-if="!orderVis">+</span>
          </h4>
          <h4 class="order-btn text-secondary" v-on:click="toggleDelete()">
            Delete Dimensions
            <span class="order-span" v-if="deleteVis">-</span>
            <span class="order-span" v-if="!deleteVis">+</span>
          </h4>
          <!-- add dimensions component -->
          <add
            v-if="addVis">
          </add>
          <!-- order dimensions component -->
          <order
            v-if="orderVis">
          </order>
          <!-- delete dimensions component -->
          <delete
            v-if="deleteVis">
          </delete>
        </div>
</template>



<script>

import Order from './ordercomponent.vue';
import DeleteDimensions from './deletedimensionscomponent.vue';
import AddDimensions from './adddimensionscomponent.vue';
import LoadDots from '../pages/loaddots.vue';
import { errorHandle } from '../../helper/errors';

export default {
    components: {
      'load-dots': LoadDots,
      'order': Order,
      'delete': DeleteDimensions,
      'add': AddDimensions,
    },

    data(){
      return{
        orderVis: false,
        deleteVis: false,
        addVis: false,
      }

  },
  mounted(){

     // call method to alert sidemenu that the current page is in a sub menu.
     this.pageOpen();
     setTimeout(() => {
       this.templateHeight();
     },500);
  },
  methods: {
    pageOpen: function(){
      this.$root.$emit('subMenu', 'Products');
    },
    templateHeight: function(){
      var elem = document.getElementById('dashboardOrderContainer').offsetHeight;
      this.sideBarHeight(elem);
      console.log(elem);
    },
    sideBarHeight: function(elem){
      this.$root.$emit('sideBarHeight', elem);
    },
    toggleOrder: function(){
      this.orderVis = !this.orderVis;
    },
    toggleDelete: function(){
      this.deleteVis = !this.deleteVis;
    },
    toggleAdd: function(){
      this.addVis = !this.addVis;
    },

  }


}


</script>
<style>
 .order-btn {
   cursor: pointer;
 }
 .order-span {
   font-size: 25px;
 }
</style>
