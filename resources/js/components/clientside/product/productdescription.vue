<template>
      <div :class="templateName + '-product-description-wrapper'">
        <p>{{ productDescription }}</p>
      </div>
</template>


<script>

import ProductRatings from './productratings.vue';

export default {
  components: {
    'product-ratings': ProductRatings,
  },
  props: {
    'productDescription': String,
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
