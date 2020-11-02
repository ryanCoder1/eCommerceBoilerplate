import { getLocalAdmin } from "./helper/auth";
import { getLocalInDashboard } from "./helper/auth";

const admin = getLocalAdmin();
const inDashboard = getLocalInDashboard();


export default {

namespaced: true,
state: {
  currentAdmin: admin, // The account of user
  isLoggedInDashboard: inDashboard,
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
  currentAdmin(state){
    return state.currentAdmin;
  },
  authError(state){
    return state.auth_error;
  },
  friendId(state){
    return state.friend_id;
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
    state.loading = false;
    state.currentAdmin = Object.assign({}, payload.admin, {token: payload.access_token});
    state.isLoggedInDashboard = true;
    localStorage.setItem("inDashboard", JSON.stringify(state.isLoggedInDashboard));
    localStorage.setItem("admin", JSON.stringify(state.currentAdmin));

  },
  loginFailed(state, payload){
    state.loading = false;
    state.auth_error = payload;

  },
  logout(state){
    localStorage.removeItem("admin");
    state.isLoggedInDashboard = false;
    localStorage.setItem("inDashboard", JSON.stringify(state.isLoggedInDashboard));
    state.currentAdmin = null;
  },
  logoutQuiet(state){
    state.isLoggedInDashboard = false;
    localStorage.setItem("inDashboard", JSON.stringify(state.isLoggedInDashboard));
    state.currentAdmin = null;
  },
  alertLogout(state){
    state.alertLogout = 'User session is over, must log in.';
  },
  getFillForm(state, payload){
    state.fillForm = payload;
  },
  updateUser(state,  payload){
     console.log("payload " + payload);
     localStorage.setItem('userInfo', JSON.stringify(payload.userInfo[0]));
     state.updatedUser = JSON.parse(localStorage.getItem('userInfo'));
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
  logoutQuiet: ({commit}) => {
      commit('logoutQuiet');
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
