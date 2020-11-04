<template>
      <div class="info-price">
        <p
        class="info-price-original"
        :class="[product.sale_price != 'null' ? 'info-price-is-sale' : '']"
        >
          <span v-if="product.sale_price != 'null'">Originally </span>
          <span v-else>Price </span>
          <span>${{ product.price }}</span>
        </p>
        <p class="info-price-actual">
          <span class="info-price-actual-price">${{ product.price - product.sale_price }} </span>
          <span class="info-sale-price">${{ product.sale_price }}</span>
        </p>
      </div>
</template>


<script>



export default {
  props:[
    'product',
  ],
  components: {

  },
data(){
  return{

  }
},


}


</script>
<style>

</style>
