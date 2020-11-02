import { getCategoryId } from "./helper/products";

 const categoryId = getCategoryId();

export default {

namespaced: true,

state: {
  categoryId: categoryId,
},

getters: {
  getCategory(state){
    return state.categoryId;
  },

},
mutations: {
  getCategoryId(state, payload) {
    state.categoryId = payload;
    localStorage.setItem("categoryId", JSON.stringify(state.categoryId));
  },

},
actions: {
  getCategoryId: ({commit}, payload) => {
      commit('getCategoryId', payload);
  },

}

};
