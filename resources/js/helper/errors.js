// handle error functionality in vue components

export function errorHandle(error, componentCall, self){
  console.log(error);
  if(error.response.status === 401){
     self.$router.push('/admin/logout');
   }
  else if(error.response.status === 419){
      self.$router.push('/admin/logout');
    }
  else if(error.response.status == 500){
      self.$store.dispatch('userErrors/featureNeedsService', componentCall);
      self.$router.push('/featureneedsservicedashboard');
    }
}
