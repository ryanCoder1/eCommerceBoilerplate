<template>
      <div class="info-title">
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


}


</script>
<style>

</style>
