import { getCategoryId } from "./helper/products";
import { getProductId } from "./helper/products";

const categoryId = getCategoryId();
const productId = getProductId();

export default {

namespaced: true,

state: {
  categoryId: categoryId,
  productId: productId,
},

getters: {
  getCategory(state){
    return state.categoryId;
  },
  getProduct(state){
    return state.productId;
  },

},
mutations: {
  getCategoryId(state, payload) {
    state.categoryId = payload;
    localStorage.setItem("categoryId", JSON.stringify(state.categoryId));
  },
  getProductId(state, payload) {
    state.productId = payload;
    localStorage.setItem("productId", JSON.stringify(state.productId));
  },

},
actions: {
  getCategoryId: ({commit}, payload) => {
      commit('getCategoryId', payload);
  },
  getProductId: ({commit}, payload) => {
      commit('getProductId', payload);
  },

}

};
