



export default {

namespaced: true,
state: {
  storeNotice: false,
  featureNeedsService: [],

},
getters: {

},
mutations: {
  featureNeedsService(state, payload){
    state.storeNotice = true;
    if(state.featureNeedsService.length){
      if(state.featureNeedsService.indexOf(payload) == -1){
          state.featureNeedsService.push(payload);
        }
    }else{
        state.featureNeedsService.push(payload);
    }
}

},
actions: {
  featureNeedsService: ({commit}, payload) => {
    commit('featureNeedsService', payload);
  }
}
};
