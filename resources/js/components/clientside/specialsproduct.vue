
<template>
    <div>
      <div class="inline-block image-block">
        <img  class="info-img activeInfo activeImg" :src="'../storage/images/' + special.image_path + '/' + special.image_name" alt="product image">
      </div>
      <div class="inline-block info-block">
        <h5 class="title">{{ special.title }}</h5>
        <p class="desc">{{ special.description.substring(0,125) + ".." }}</p>
        <p class="price">
          <span class="dollar-sign">$</span><span class="total">{{ special.price - special.sale_price }}</span>
          <span class="sale text-danger">
          {{ special.sale_price }}
          </span>
        </p>
        <div class="slide-color-container">
          <div
          class="slide-bg-color-hex "
          v-bind:style="[group.product_id == special.id ? {backgroundColor: group.color} : '' ]"
          v-for="(group, index) in groups[specialIndex]" :key="index">
          </div>
        </div>
    </div>


    </div>
</template>



<script>


export default {

  components: {

  },
  props: {
    'special': Object,
    'groups': Array,
    'specialIndex': Number,


  },
  data(){
    return{
      noSpecials: false,
      totalPrice: 0,
      uniqueGroups: [],
    }
  },
  mounted(){

     // this.filterColors();
  },
  methods: {
      filterColors: function(){
        console.log(this.specialIndex);
      const unique = [...new Set(this.groups.map(item => item.group))];
      console.log(unique);
      }
    }

}


</script>
<style>


</style>
