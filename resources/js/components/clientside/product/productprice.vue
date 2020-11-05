<template>
  <div>
      <div class="info-price" v-if="Object.keys(this.colorClicked).length === 0">
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

      <!-- if colorClicked from color component -->
      <div class="info-price" v-if="Object.keys(this.colorClicked).length !== 0">
        <p
        class="info-price-original"
        :class="[colorClicked.sale_price != 0 ? 'info-price-is-sale' : '']"
        v-if="colorClicked.sale_price != 0"
        >
          <span v-if="colorClicked.sale_price != 0">Originally </span>
          <span v-else>Price </span>
          <span>${{ colorClicked.price }}</span>
        </p>
        <p class="info-price-actual">
          <span class="info-price-actual-price">${{ colorClicked.price - colorClicked.sale_price }} </span>
          <span class="info-sale-price" v-if="colorClicked.sale_price != 0">${{ colorClicked.sale_price }}</span>
        </p>
      </div>
    </div>
</template>


<script>



export default {
  props:[
    'product',
    'colorClicked',
  ],
  components: {

  },
data(){
  return{

  }
},
watch: {
  colorClicked: function(newColor, oldColor){
    if(newColor){
      if(Object.keys(this.colorClicked).length !== 0){
        console.log('reading it');
      }
    }
  }
}


}


</script>
<style>

</style>
