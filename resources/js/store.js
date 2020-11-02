import { getLocalNavHeaderView } from "./helper/auth";
const navHeaderViews = getLocalNavHeaderView();


import userInfo from "./modular-vuex-userInfo";
import admin from "./modular-vuex-admin";
import userErrors from "./modular-vuex-userErrors";
import products from "./modular-vuex-products";

export default {

  namespaced: true,
  modules: {
    userInfo,
    userErrors,
    admin,
    products,
  },

  state: {

        friendsRequest: '',
        friendsRequestCount: '',
        showFriendsRequest: '',
        friendsRequestAlert: false,
        outfitsViewYouRequest: '',
        outfitsViewYouCount: '',
        outfitsViewYouRequestAlert: false,
        outfitsYouViewRequest: '',
        outfitsYouViewCount: '',
        outfitsYouViewRequestAlert: false,
        searchOutfitsResults: [],
        responseSearchOutfits: '',
        listFriends: false,
        checkFriends: false,
        navHeaderView: navHeaderViews,
        weatherModView: '',
        chatNewsId: '',
        refreshNewsFeed: false,
  },
  getters: {

        navHeaderViews(state){
          return state.navHeaderView;
        }

  },
  mutations: {

        setNavHeaderView(state, payload){
          console.log("another " + payload);
          state.navHeaderView = payload;
          localStorage.setItem("navHeaderView", JSON.stringify(state.navHeaderView));
        },

  },
  actions: {

        setNavHeaderView(context){
          context.commit('setNavHeaderView', payload);
        },

  },
// strict mode in the dev environment.
 strict: process.env.NODE_ENV !== 'production',
};