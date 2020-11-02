import { getLocalUser } from "./helper/auth";

const user = getLocalUser();

export default {

namespaced: true,

state: {
  currentUser: user, // The account of user
  isLoggedIn: !!user,
  loading: false,
  auth_error: null,
  alertLogout: '',
  fillForm: '',
},

getters: {
  isLoading(state){
    return state.loading;
  },
  isLoggedIn(state){
    return state.isLoggedIn;
  },
  currentUser(state){
    return state.currentUser;
  },
  authError(state){
    return state.auth_error;
  },


    navOutfit(state){
      return state.nav_outfit;
    },
},
mutations: {
  login(state, rootState) {
    state.loading = true;
    state.auth_error = null;

  },
loginSuccess(state, payload){

    state.auth_error = null;
    state.isLoggedIn = true;
    state.loading = false;
    state.currentUser = Object.assign({}, payload.user, {token: payload.access_token});

    localStorage.setItem("user", JSON.stringify(state.currentUser));

  },
  loginFailed(state, payload){
    state.loading = false;
    state.auth_error = payload;

  },
  logout(state, payload){
    localStorage.removeItem("user");
    state.isLoggedIn = false;
    state.currentUser = null;
  },
  alertLogout(state){
    state.alertLogout = 'User session is over, must log in.';
  },
  getFillForm(state, payload){
    state.fillForm = payload;
  },


},
actions: {
  login: ({commit}) => {
      commit('login');
  },
  loginSuccess: ({commit}, payLoad) => {
      commit('loginSuccess', payLoad)
   },
  loginFailed: ({commit}) => {
      commit('loginFailed', payload);
  },
  logout: ({commit}) => {
      commit('logout');
  },
  alertLogout: ({commit}) => {
      commit('alertLogout');
  },
  updateUser: ({commit}, payload) => {
      commit('updateUser', payload);
  },
  getFillForm: ({commit}, payload) => {
    commit('getFillForm', payload);
  },
}

};
