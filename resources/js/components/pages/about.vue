<template>

      <div >
         <form  id="form">

                  <h3>Login</h3>



                  <input   type="email"  placeholder="Email" />






                <input  type="password" placeholder="Password" />



                     <br/>


                  <input type="button"  value="Log In" />

          </form>

       </div>


</template>



<script>


export default {

  data(){
    return{
     title: 'sign in',




    }
  },
  created() {



  },
 computed: {

},

  methods: {






      },

      watch: {




   }
}


</script>
<style>
#form {
  position: relative;
  top: 200px;
}
</style>
