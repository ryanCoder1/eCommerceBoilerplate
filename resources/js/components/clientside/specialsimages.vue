<template>
    <div>
      <img :class="[templateName + '-info-img-block', templateName + '-active-img-block']" :src="'../storage/images/' + special.image_path + '/' + special.image_name" alt="product image">
    </div>
</template>



<script>


export default {

  props: {
    'special': Object,
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
.infoImgBlock {
  width: 100%;
  height: 100%;
  margin: 0;
  padding: 0;
  cursor: pointer;
  /* -webkit-filter: blur(5px);
 filter: blur(5px); */
  transition: .3s;
}
</style>
