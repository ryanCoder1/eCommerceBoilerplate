<template>
      <div class="product-description-wrapper">
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


}


</script>
<style>

</style>
