<template>
    <div>
      <img  class="featured-slide-img featuredActiveImg" :src="'../storage/images/' + feature.image_path + '/' + feature.image_name" alt="product image">
    </div>
</template>



<script>


export default {

  props: {
    'feature': Object,
  },
  data(){
    return{
      noSpecials: false,
    }
  },
  mounted(){
    console.log(this.feature);
  },
  methods: {

    }

}


</script>
<style>

</style>
