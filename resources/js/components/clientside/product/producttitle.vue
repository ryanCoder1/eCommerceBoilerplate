<template>
      <div :class="templateName + '-info-title'">
        <p>{{ productTitle }}</p>
        <product-ratings>
        </product-ratings>
      </div>
</template>


<script>

import ProductRatings from './productratings.vue';

export default {
  components: {
    'product-ratings': ProductRatings,
  },
  props: {
    'productTitle': String,
  },
data(){
  return{

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
