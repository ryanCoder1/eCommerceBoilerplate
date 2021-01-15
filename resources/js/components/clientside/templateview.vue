<template>
  <div>
    <component v-bind:is="componentFile"></component>
  </div>
</template>

<script>
  export default {
    data(){
      return{

      }
    },
    computed: {
      componentFile() {
        if(this.$store.state.templateView){
           return this.importComponent(this.$store.state.templateView)
         }

      }
     },
     methods: {
      importComponent(path) {
       return () => import(`./templates/${path}/home.vue`)
      }
     }
}
</script>

<style lang="css">

</style>
