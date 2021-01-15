<template>
  <div :class="templateName + '-info-price'">
      <div  v-if="Object.keys(this.colorClicked).length === 0">
        <p
        :class="[product.sale_price != 'null' ? 'info-price-is-sale' : '', templateName + '-info-price-original']"
        >
          <span v-if="product.sale_price != 'null'">Originally </span>
          <span v-else>Price </span>
          <span>${{ product.price }}</span>
        </p>
        <p :class="templateName + '-info-price-actual'">
          <span :class="templateName + '-info-price-actual-price'">${{ product.price - product.sale_price }} </span>
          <span :class="templateName + '-info-sale-price'">${{ product.sale_price }}</span>
        </p>
      </div>

      <!-- if colorClicked from color component -->
      <div v-if="Object.keys(this.colorClicked).length !== 0">
        <p
        :class="[colorClicked.sale_price != 0 ? 'info-price-is-sale' : '', templateName + '-info-price-original']"
        v-if="colorClicked.sale_price != 0"
        >
          <span v-if="colorClicked.sale_price != 0">Originally </span>
          <span v-else>Price </span>
          <span>${{ colorClicked.price }}</span>
        </p>
        <p :class="templateName + '-info-price-actual'">
          <span :class="templateName + '-info-price-actual-price'">${{ colorClicked.price - colorClicked.sale_price }} </span>
          <span :class="templateName + '-info-sale-price'" v-if="colorClicked.sale_price != 0">${{ colorClicked.sale_price }}</span>
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
},
computed: {
  templateName: function(){
    if(this.$store.state.templateView){
       return this.$store.state.templateView;
     }
  }
},


}


</script>
<style>

</style>
