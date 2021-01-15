<template>
  <div :class="templateName + '-info-in-stock'">
      <div  v-if="Object.keys(this.colorClicked).length === 0">
        <span>In Stock: <span> {{ product.in_stock }}</span></span>
      </div>

      <!-- if colorClicked from color component -->
      <div v-if="Object.keys(this.colorClicked).length !== 0">
        <span>In Stock: <span> {{ colorClicked.in_stock }}</span></span>
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
