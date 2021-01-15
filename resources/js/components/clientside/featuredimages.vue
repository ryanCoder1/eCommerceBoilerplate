<template>
    <div>
      <img  :class="[templateName + '-featured-slide-img', templateName + '-featured-active-img']" :src="'../storage/images/' + feature.image_path + '/' + feature.image_name" alt="product image">
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
  computed: {
    templateName: function(){
      if(this.$store.state.templateView){
         return this.$store.state.templateView;
       }
    }
  }

}


</script>
<style>

</style>
