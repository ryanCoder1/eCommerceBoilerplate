
<template>
    <div>
      <div :class="[templateName + '-inline-block', templateName + '-image-block']">
        <img  :class="[templateName + '-info-img', templateName + '-active-info', templateName + '-active-img']" :src="'../storage/images/' + special.image_path + '/' + special.image_name" alt="product image">
      </div>
      <div :class="[templateName + '-inline-block', templateName + '-info-block']">
        <h5 :class="templateName + '-title'">{{ special.title }}</h5>
        <p :class="templateName + '-desc'">{{ special.description.substring(0,125) + ".." }}</p>
        <p :class="templateName + '-price'">
          <span :class="templateName + '-dollar-sign'">$</span><span class="total">{{ special.price - special.sale_price }}</span>
          <span :class="[templateName + '-sale', templateName + '-text-danger']">
          {{ special.sale_price }}
          </span>
        </p>
        <div :class="templateName + '-slide-color-container'">
          <div
          :class="templateName + '-slide-bg-color-hex' "
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
  computed: {
    templateName: function(){
      if(this.$store.state.templateView){
         return this.$store.state.templateView;
       }
    }
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
